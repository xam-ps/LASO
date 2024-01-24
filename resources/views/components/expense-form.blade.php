@props(['cost_types', 'supplier_list', 'now', 'expense'])

@if (isset($expense))
<form class="my-2" method="post" action="{{ route('expense.update', ['id'=>$expense->id]) }}" class="my-2">
    @method('PUT')
    @else
    <form class="my-2" method="post" action="{{ route('expense.store') }}">
        @endif
        @csrf
        <label for="billing_date">Rechnungsdatum:</label><br>
        @error('billing_date')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="date" id="billing_date" name="billing_date"
            value="{{ old('billing_date', $expense->billing_date ?? $now) }}"><br>

        <label for="payment_date">Zahlungseingang:</label><br>
        @error('payment_date')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="date" id="payment_date" name="payment_date"
            value="{{ old('payment_date', $expense->payment_date ?? $now) }}"><br>

        <label for="supplier_name">Lieferant:</label><br>
        @error('supplier_name')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="supplier_name" name="supplier_name" list="suppliers"
            value="{{ old('supplier_name', $expense->supplier_name ?? '') }}"><br>
        <datalist id="suppliers">
            @foreach ($supplier_list as $supplier)
            <option>{{$supplier}}</option>
            @endforeach
        </datalist>

        <label for="product_name">Produkt:</label><br>
        @error('product_name')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="product_name" name="product_name"
            value="{{ old('product_name', $expense->product_name ?? '') }}"><br>

        <label for="invoice_number">Rechnungsnummer:</label><br>
        @error('invoice_number')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="invoice_number" name="invoice_number"
            value="{{ old('invoice_number', $expense->invoice_number ?? '') }}"><br>

        <label for="net">Netto:</label><br>
        @error('net')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="net" name="net" type="number" value="{{ old('net', $expense->net ?? '') }}" min="0" step="0.01">
        €<br>

        <label for="tax">Steuer:</label><br>
        @error('tax')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="tax" name="tax" type="number" value="{{ old('tax', $expense->tax ?? '') }}" min="0" step="0.01"
            style="width: 148px"> €
        <input id="tax_rate" type="number" min="0" max="30" value="19" style="width: 78px"> %
        <br>

        <label for="gross">Brutto:</label><br>
        @error('gross')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="gross" name="gross" type="number" value="{{ old('gross', $expense->gross ?? '') }}" min="0"
            step="0.01">
        €<br>

        <label for="cost_type">Typ:</label>
        <span id="costTypeInfo"
            class="cursor-pointer mb-2 ml-1 border-black dark:border-gray-200 inline-block rounded-full leading-none"
            style="padding: 1px 8px; border-width: 1px" tabindex="0">i</span><br>
        @error('cost_type')
        <div class="alert">{{ $message }}</div>
        @enderror
        <select name="cost_type" id="cost_type">
            @foreach ($cost_types as $cost_type)
            @if (isset($expense))
            <option @if ($cost_type->id == $expense->cost_type_id) selected @endif
                @else
            <option @if ($cost_type->id == old('cost_type')) selected @endif
                @endif
                style="background: #{{$cost_type->color_code}}" value="{{$cost_type->id}}">
                {{$cost_type->short_name}}
            </option>
            @endforeach
        </select>
        <br>

        <div class="hidden">
            <label for="depreciation">Abschreibedauer:</label><br>
            @error('depreciation')
            <div class="alert">{{ $message }}</div>
            @enderror
            <input id="depreciation" name="depreciation" type="number"
                value="{{ old('depreciation', $expense->depreciation ?? '') }}" min="0" max="30" step="1">
        </div>

        <div class="text-center">
            <x-primary-button class="mt-4">
                {{ __('Speichern') }}
            </x-primary-button>
        </div>
    </form>

    <div id="costTypeOverlay"
        class="hidden fixed top-0 left-0 w-screen h-screen bg-white dark:bg-gray-900 bg-opacity-80 dark:bg-opacity-80 p-8 text-center flex items-center dark:text-white">
        <div class="mx-auto w-full md:w-1/3 bg-white dark:bg-gray-800 border-gray-200 border p-8 relative">
            <p class="cursor-pointer text-lg absolute top-2 right-5 trans">✕</p>
            <h2>Kostentypen:</h2>
            <table>
                @foreach ($cost_types as $cost_type)
                <tr title="{{$cost_type->description}}">
                    <td style="background: #{{$cost_type->color_code}}; width: 20px"></td>
                    <td>{{$cost_type->short_name}}</td>
                    <td>{{$cost_type->full_name}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>