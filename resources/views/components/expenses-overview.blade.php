@props(['expenses', 'expNetSum', 'expTaxSum', 'expGrossSum'])

<div class="max-w-max mx-auto sm:px-6 lg:px-8 mt-4">
    <div id="expenses" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
        <div class="flex justify-end mt-4 absolute right-4">
            <a href="{{ route('expense.create') }}">
                <x-primary-button>
                    {{ __('+') }}
                </x-primary-button>
            </a>
        </div>
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h1>Ausgaben</h1>
            <div class="total_amounts flex flex-row text-center">
                <div>
                    <p>Netto</p>
                    <span>{{Number::currency($expNetSum, in: 'EUR', locale: 'de')}}</span>
                </div>
                <div>
                    <p>Steuer</p>
                    <span>{{Number::currency($expTaxSum, in: 'EUR', locale: 'de')}}</span>
                </div>
                <div>
                    <p>Brutto</p>
                    <span>{{Number::currency($expGrossSum, in: 'EUR', locale: 'de')}}</span>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Rechnungsdatum:</th>
                        <th>Zahlungsdatum:</th>
                        <th>Firma:</th>
                        <th>Produkt:</th>
                        <th>Rechnungsnummer:</th>
                        <th>Netto:</th>
                        <th>Steuer:</th>
                        <th>Brutto:</th>
                        <th>Art:</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->billing_date }}</td>
                        <td>{{ $expense->payment_date }}</td>
                        <td>{{ $expense->supplier_name }}</td>
                        <td>{{ $expense->product_name }}</td>
                        <td>{{ $expense->invoice_number }}</td>
                        <td class="currency">{{Number::currency($expense->net, in: 'EUR', locale: 'de')}}</td>
                        <td class="currency">{{Number::currency($expense->tax, in: 'EUR', locale: 'de')}}</td>
                        <td class="currency">{{Number::currency($expense->gross, in: 'EUR', locale: 'de')}}</td>
                        <td style="background: #{{$expense->costType->color_code}}" class="text-gray-900">{{
                            $expense->costType->short_name }}
                        </td>
                        <td class="hover:bg-slate-600 cursor-pointer rounded-sm hover:text-slate-100 p-0!">
                            <a href="{{ route('expense.edit', ['id' => $expense->id]) }}">
                                &#9998;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>