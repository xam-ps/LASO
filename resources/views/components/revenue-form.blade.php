@props(['customer_list', 'now', 'revenue'])

@if (isset($revenue))
<form class="my-2" method="post" action="{{ route('revenue.update', ['id'=>$revenue->id]) }}">
    @method('PUT')
    @else
    <form class="my-2" method="post" action="{{ route('revenue.store') }}">
        @endif
        @csrf
        <label for="billing_date">Rechnungsdatum:</label><br>
        @error('billing_date')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="date" id="billing_date" name="billing_date"
            value="{{ old('billing_date', $revenue->billing_date ?? $now) }}"><br>

        <label for="payment_date">Zahlungseingang:</label><br>
        @error('payment_date')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="date" id="payment_date" name="payment_date"
            value="{{ old('payment_date', $revenue->payment_date ?? '') }}"><br>

        <label for="customers_name">Kunde:</label><br>
        @error('company_name')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="customers_name" name="company_name" list="customers"
            value="{{ old('company_name', $revenue->company_name ?? '') }}"><br>
        <datalist id="customers">
            @foreach ($customer_list as $customer)
            <option>{{$customer}}</option>
            @endforeach
        </datalist>

        <label for="invoice_number">Rechnungsnummer:</label><br>
        @error('invoice_number')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="invoice_number" name="invoice_number"
            value="{{ old('invoice_number', $revenue->invoice_number ?? '') }}"><br>

        <label for="net">Netto:</label><br>
        @error('net')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="net" name="net" type="number" value="{{ old('net', $revenue->net ?? '') }}" min="0" step="0.01">
        €<br>

        <label for="tax">Steuer:</label><br>
        @error('tax')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="tax" name="tax" type="number" value="{{ old('tax', $revenue->tax ?? '') }}" min="0" step="0.01"
            style="width: 148px">
        €
        <input id="tax_rate" type="number" min="0" max="30" value="{{ env('DEFAULT_TAX_RATE') }}" style="width: 78px"> %
        <br>

        <label for="gross">Brutto:</label><br>
        @error('gross')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="gross" name="gross" type="number" value="{{ old('gross', $revenue->gross ?? '') }}" min="0"
            step="0.01">
        €<br>

        <div class="text-center">
            <x-primary-button class="mt-4">
                {{ __('Speichern') }}
            </x-primary-button>
        </div>
    </form>