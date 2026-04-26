#!/usr/bin/env bash

set -euo pipefail

abort() {
    echo "ERROR: $1" >&2
    exit 1
}

remote_checks_enabled() {
    [ "${LASO_SKIP_REMOTE_CHECKS:-0}" != "1" ]
}

verification_remote() {
    if [ -n "${LASO_READONLY_REMOTE:-}" ]; then
        echo "$LASO_READONLY_REMOTE"
        return
    fi

    if git remote get-url public-origin >/dev/null 2>&1; then
        echo "public-origin"
        return
    fi

    echo "origin"
}

require_clean_worktree() {
    if ! git diff --quiet || ! git diff --cached --quiet; then
        abort "Working tree is not clean. Commit or stash your changes before running the release script."
    fi

    if [ -n "$(git ls-files --others --exclude-standard)" ]; then
        abort "Untracked files detected. Clean up the working tree before running the release script."
    fi
}

require_main_branch() {
    local branch
    branch=$(git branch --show-current)

    if [ "$branch" != "main" ]; then
        abort "Release script must be run on main. Current branch: $branch"
    fi
}

require_up_to_date_main() {
    local remote

    if ! remote_checks_enabled; then
        echo "Skipping remote main check because LASO_SKIP_REMOTE_CHECKS=1"
        return
    fi

    remote=$(verification_remote)

    echo "Checking remote main branch via $remote..."
    if ! git fetch "$remote" main; then
        abort "Unable to fetch $remote/main from $(git remote get-url "$remote"). Verify network access, or rerun with LASO_SKIP_REMOTE_CHECKS=1 for a local-only release."
    fi

    local local_head remote_head merge_base
    local_head=$(git rev-parse HEAD)
    remote_head=$(git rev-parse "$remote/main")
    merge_base=$(git merge-base HEAD "$remote/main")

    if [ "$local_head" != "$remote_head" ]; then
        if [ "$local_head" = "$merge_base" ]; then
            abort "Local main is behind $remote/main. Pull the latest changes before releasing."
        fi

        if [ "$remote_head" = "$merge_base" ]; then
            abort "Local main is ahead of $remote/main. Push or reconcile main before releasing."
        fi

        abort "Local main and $remote/main have diverged. Reconcile the branch before releasing."
    fi
}

read_current_version() {
    local version
    version=$(sed -nE 's/.*"version": *"([^"]+)".*/\1/p' composer.json | head -1)

    if [ -z "$version" ]; then
        abort "No version field found in composer.json"
    fi

    echo "$version"
}

compute_next_version() {
    local current_version=$1
    local major minor patch

    IFS='.' read -r major minor patch <<< "$current_version"

    if [ -z "${major:-}" ] || [ -z "${minor:-}" ] || [ -z "${patch:-}" ]; then
        abort "Version '$current_version' is not in MAJOR.MINOR.PATCH format."
    fi

    echo "$major.$minor.$((patch + 1))"
}

ensure_tag_available() {
    local tag=$1
    local remote_tag_status=0
    local remote

    if git rev-parse "$tag" >/dev/null 2>&1; then
        abort "Tag $tag already exists locally."
    fi

    if remote_checks_enabled; then
        remote=$(verification_remote)
        git ls-remote --exit-code --tags "$remote" "refs/tags/$tag" >/dev/null 2>&1 || remote_tag_status=$?

        if [ "$remote_tag_status" -eq 0 ]; then
            abort "Tag $tag already exists on $remote."
        fi

        if [ "$remote_tag_status" -ne 2 ] && [ "$remote_tag_status" -ne 0 ]; then
            abort "Unable to verify remote tags on $remote. Verify network access, or rerun with LASO_SKIP_REMOTE_CHECKS=1 for a local-only release."
        fi
    else
        echo "Skipping remote tag check because LASO_SKIP_REMOTE_CHECKS=1"
    fi
}

ensure_only_expected_files_changed() {
    local changed_file
    local allowed=(
        "composer.json"
        "composer.lock"
        "package.json"
        "package-lock.json"
    )

    while IFS= read -r changed_file; do
        [ -z "$changed_file" ] && continue

        case " ${allowed[*]} " in
            *" $changed_file "*) ;;
            *)
                abort "Unexpected tracked file changed during release: $changed_file"
                ;;
        esac
    done < <(git diff --name-only)
}

stage_release_files() {
    local files_to_stage=()
    local candidate

    for candidate in composer.json composer.lock package.json package-lock.json; do
        if [ -f "$candidate" ]; then
            files_to_stage+=("$candidate")
        fi
    done

    git add -- "${files_to_stage[@]}"
}

echo "Using Node version: $(node -v)"

require_clean_worktree
require_main_branch
require_up_to_date_main

echo "Reading current version from composer.json..."
CURRENT_VERSION=$(read_current_version)
NEW_VERSION=$(compute_next_version "$CURRENT_VERSION")
TAG="v$NEW_VERSION"

echo "Current version: $CURRENT_VERSION"
echo "Next version: $NEW_VERSION"

ensure_tag_available "$TAG"

echo "Step 1: Updating npm dependencies..."
npm update

echo "Step 2: Updating composer dependencies..."
composer update

echo "Step 3: Building frontend..."
npm run build

echo "Step 4: Running Laravel tests..."
php artisan test

echo "Tests passed."
echo "Step 5: Bumping version to $NEW_VERSION..."
sed -i -E "s/\"version\": *\"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" composer.json

ensure_only_expected_files_changed

echo "Step 6: Staging release files..."
stage_release_files

if git diff --cached --quiet; then
    abort "No release changes were staged."
fi

echo "Step 7: Creating commit..."
git commit -m "update dependencies"

echo "Step 8: Creating tag..."
git tag -a "$TAG" -m "Release version $NEW_VERSION"

echo "Release complete: $TAG"
echo "Push the release from your local machine with:"
echo "git push origin main --follow-tags"
