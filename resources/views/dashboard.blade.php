<x-app-layout>
    <x-slot name="header">
        <div id="dashboard_header" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <ul>
                @foreach ($years as $yearN)
                <li><a href="{{ route('dashboard.year', ['year' => $yearN]) }}">{{ $yearN }}</a></li>
                @endforeach
            </ul>
        </div>
    </x-slot>

    <div id="dashboard_page" class="py-12">
        <x-revenue-overview :$revenues :$revNetSum :$revTaxSum :$revGrossSum :$year />
        <x-expenses-overview :$expenses :$expNetSum :$expTaxSum :$expGrossSum :$year />
</x-app-layout>