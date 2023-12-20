<x-app-layout>
    <div id="create_revenue_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Neue Einnahme anlegen</h1>
                    <form method="post" action="{{ route('revenue.store') }}" class="my-2">
                        @csrf
                        <label for="billing_date">Rechnungsdatum:</label><br>
                        <input type="date" id="billing_date" name="billing_date" value="{{ old('billing_date') }}"><br>

                        <label for="payment_date">Zahlungseingang:</label><br>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date') }}"><br>

                        <label for="customers_name">Kunde:</label><br>
                        <input id="customers_name" name="company_name" list="customers"
                            value="{{ old('company_name') }}"><br>
                        <datalist id="customers">
                            @foreach ($customer_list as $customer)
                            <option>{{$customer}}</option>
                            @endforeach
                        </datalist>

                        <label for="invoice_number">Rechnungsnummer:</label><br>
                        <input id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}"><br>

                        <label for="net">Netto:</label><br>
                        <input id="net" name="net" type="number" value="{{ old('net') }}" min="0" step="0.01"> €<br>

                        <label for="tax">Steuer:</label><br>
                        <input id="tax" name="tax" type="number" value="{{ old('tax') }}" min="0" step="0.01"
                            style="width: 148px"> €
                        <input id="tax_rate" type="number" min="0" max="30" value="19" style="width: 78px"> %
                        <br>

                        <label for="gross">Brutto:</label><br>
                        <input id="gross" name="gross" type="number" value="{{ old('gross') }}" min="0" step="0.01">
                        €<br>

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
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const net = document.querySelector("#net");
            const tax = document.querySelector("#tax");
            const tax_rate = document.querySelector("#tax_rate");
            const gross = document.querySelector("#gross");
            
            net.addEventListener("keyup", calcValuesForNet);
            net.addEventListener("change", calcValuesForNet);
            tax_rate.addEventListener("keyup", calcValuesForNet);
            tax_rate.addEventListener("change", calcValuesForNet);
            gross.addEventListener("keyup", calcValuesForGross);
            gross.addEventListener("change", calcValuesForGross);

            function calcValuesForNet(){
                tax.value = (parseFloat(net.value)*parseFloat(tax_rate.value)/100).toFixed(2);
                gross.value = (parseFloat(net.value)+parseFloat(tax.value)).toFixed(2);
            }
            function calcValuesForGross(){
                tax.value = (parseFloat(gross.value)*parseFloat(tax_rate.value)/(100+parseFloat(tax_rate.value))).toFixed(2);
                net.value = (parseFloat(gross.value)-parseFloat(tax.value)).toFixed(2);
            }
        });
    </script>
    @endsection

</x-app-layout>