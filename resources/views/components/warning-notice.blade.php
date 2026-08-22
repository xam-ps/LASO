@props(['title'])

<div {{ $attributes->merge(['class' => 'my-4 flex items-start gap-3 rounded-md border-l-4 border border-l-amber-500
    border-amber-300 bg-amber-50 p-4 text-amber-900 dark:border-amber-500/50 dark:border-l-amber-500
    dark:bg-amber-950/40 dark:text-amber-100 print:border-black print:bg-transparent print:text-black']) }}>
    <svg class="mt-0.5 h-5 w-5 shrink-0 fill-amber-600 dark:fill-amber-400 print:fill-black" viewBox="0 0 512 512"
        aria-hidden="true">
        <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        <path
            d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
    </svg>
    <div>
        <p class="font-semibold"><span class="sr-only">Warnung: </span>{{ $title }}</p>
        <p class="mt-1 text-sm">{{ $slot }}</p>
    </div>
</div>