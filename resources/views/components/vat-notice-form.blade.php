@props(['vatNotice', 'now', 'remainingRevenueTax', 'remainingExpenseTax', 'year'])

@if (isset($vatNotice))
<form class="my-2" method="post" action="{{ route('vat-notice.update', ['id' => $vatNotice->id]) }}">
    @method('PUT')
    @else
    <form class="my-2" method="post" action="{{ route('vat-notice.store') }}">
        @endif
        @csrf
        <label for="notice_date">Meldedatum:</label><br>
        @error('notice_date')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="date" id="notice_date" name="notice_date"
            value="{{ old('notice_date', $vatNotice->notice_date ?? now()->toDateString() ) }}"><br>
        <label for="vat_received">Eingenommene Umsatzsteuer:</label><br>
        @error('vat_received')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="vat_received" name="vat_received" type="number"
            value="{{ old('vat_received', $vatNotice->vat_received ?? $remainingRevenueTax) }}" min="0" step="0.01">
        €<br>

        <label for="vat_paid">Gezahlte Vorsteuer:</label><br>
        @error('vat_paid')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="vat_paid" name="vat_paid" type="number"
            value="{{ old('vat_paid', $vatNotice->vat_paid ?? $remainingExpenseTax) }}" min="0" step="0.01">
        €<br>

        @if (!isset($vatNotice))
        <input id="year" name="year" type="hidden" value={{ $year }}>
        @endif

        <div class="text-center">
            <x-primary-button class="mt-4">
                {{ __('Speichern') }}
            </x-primary-button>
        </div>
    </form>