#!/usr/bin/env bash

set -e

echo "Step 1: Updating npm dependencies..."
npm update

echo "Step 2: Updating composer dependencies..."
composer update

echo "Step 3: Building frontend..."
npm run build

echo "Step 4: Running Laravel tests..."
if ! php artisan test; then
    echo "❌ Tests failed. Aborting release."
    exit 1
fi

echo "✅ Tests passed."

echo "Step 5: Reading current version from composer.json..."
CURRENT_VERSION=$(grep '"version"' composer.json | head -1 | sed -E 's/.*"version": *"([^"]+)".*/\1/')

if [ -z "$CURRENT_VERSION" ]; then
    echo "❌ No version field found in composer.json"
    exit 1
fi

echo "Current version: $CURRENT_VERSION"

IFS='.' read -r MAJOR MINOR PATCH <<< "$CURRENT_VERSION"

NEW_PATCH=$((PATCH + 1))
NEW_VERSION="$MAJOR.$MINOR.$NEW_PATCH"

echo "Step 6: Bumping version to $NEW_VERSION..."

sed -i -E "s/\"version\": *\"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" composer.json

echo "Step 7: Adding files to git..."
git add .

echo "Step 8: Creating commit..."
git commit -m "update dependencies"

echo "Step 9: Creating tag..."
git tag -a "v$NEW_VERSION" -m "Release version $NEW_VERSION"

echo ""
echo "✅ Release complete: v$NEW_VERSION"
echo ""
echo "If you want to push the latest tag, execute:"
echo "git push origin main --tags"