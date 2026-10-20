@props(['href', 'label'])

<a href="{{ $href }}" class="corner-add" title="{{ $label }}" aria-label="{{ $label }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        aria-hidden="true">
        <path d="M12 5v14M5 12h14" />
    </svg>
</a>
