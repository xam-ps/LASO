<x-app-layout>
    <div id="edit_expense_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Ausgabe bearbeiten</h1>
                    <form method="post" action="{{ route('expense.update', ['id'=>$expense->id]) }}" class="my-2">
                        @csrf
                        <label for="billing_date">Rechnungsdatum:</label><br>
                        <input type="date" id="billing_date" name="billing_date"
                            value="{{ $expense->billing_date }}"><br>

                        <label for="payment_date">Zahlungseingang:</label><br>
                        <input type="date" id="payment_date" name="payment_date"
                            value="{{ $expense->payment_date }}"><br>

                        <label for="supplier_name">Lieferant:</label><br>
                        <input id="supplier_name" name="supplier_name" list="suppliers"
                            value="{{ $expense->supplier_name }}"><br>
                        <datalist id="suppliers">
                            @foreach ($supplier_list as $supplier)
                            <option>{{$supplier}}</option>
                            @endforeach
                        </datalist>

                        <label for="product_name">Produkt:</label><br>
                        <input id="product_name" name="product_name" value="{{ $expense->product_name }}"><br>

                        <label for="invoice_number">Rechnungsnummer:</label><br>
                        <input id="invoice_number" name="invoice_number" value="{{ $expense->invoice_number }}"><br>

                        <label for="net">Netto:</label><br>
                        <input id="net" name="net" type="number" value="{{ $expense->net }}" min="0" step="0.01"> €<br>

                        <label for="tax">Steuer:</label><br>
                        <input id="tax" name="tax" type="number" value="{{ $expense->tax }}" min="0" step="0.01"
                            style="width: 148px"> €
                        <input id="tax_rate" type="number" min="0" max="30" value="19" style="width: 78px"> %
                        <br>

                        <label for="gross">Brutto:</label><br>
                        <input id="gross" name="gross" type="number" value="{{ $expense->gross }}" min="0" step="0.01">
                        €<br>

                        <label for="cost_type">Typ:</label><br>
                        <select name="cost_type" id="cost_type">
                            @foreach ($cost_types as $cost_type)
                            <option @if ($cost_type->id == $expense->cost_type_id) selected @endif
                                style="background: #{{$cost_type->color_code}}" value="{{$cost_type->id}}">
                                {{$cost_type->short_name}}
                            </option>
                            @endforeach
                        </select>

                        <div class="hidden">
                            <label for="depreciation">Abschreibedauer:</label><br>
                            <input id="depreciation" name="depreciation" type="number"
                                value="{{ $expense->depreciation }}" min="0" max="30" step="1">
                        </div>

                        @if ($errors->any())
                        <div class="text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="text-center">
                            <x-primary-button class="mt-4">
                                {{ __('Speichern') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <form method="post" action="{{ route('expense.delete', ['id'=>$expense->id]) }}"
                        onsubmit="return confirmSubmit()">
                        @csrf
                        <x-delete-button class="mt-4 absolute right-10 bottom-8" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    @vite(['resources/js/grossCalculator.js'])
    @vite(['resources/js/toggleDepreciation.js'])
    <script>
        function confirmSubmit() {
            return window.confirm("Sicher löschen?");
        }
    </script>
    @endsection

</x-app-layout>