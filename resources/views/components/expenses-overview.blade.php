@props(['expenses', 'expNetSum', 'expTaxSum', 'expGrossSum'])

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
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
                    <span>{{ number_format($expNetSum, 2) }} €</span>
                </div>
                <div>
                    <p>Steuer</p>
                    <span>{{ number_format($expTaxSum, 2) }} €</span>
                </div>
                <div>
                    <p>Brutto</p>
                    <span>{{ number_format($expGrossSum, 2) }} €</span>
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
                        <td class="currency">{{ $expense->net }}</td>
                        <td class="currency">{{ $expense->tax }}</td>
                        <td class="currency">{{ $expense->gross }}</td>
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