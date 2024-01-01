<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='dashboard' />
    </x-slot>

    <div id="dashboard_page" class="py-4">
        <x-revenue-overview :$revenues :$revNetSum :$revTaxSum :$revGrossSum :$year />
        <x-expenses-overview :$expenses :$expNetSum :$expTaxSum :$expGrossSum :$year />
</x-app-layout>