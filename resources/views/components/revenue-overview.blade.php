@props(['revenues', 'revNetSum', 'revTaxSum', 'revGrossSum'])

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div id="revenue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
        <div class="flex justify-end mt-4 absolute right-4">
            <a href="{{ route('revenue.create') }}">
                <x-primary-button>
                    {{ __('+') }}
                </x-primary-button>
            </a>
        </div>
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h1>Einnahmen</h1>
            <div class="total_amounts flex flex-row text-center">
                <div>
                    <p>Netto</p>
                    <span>{{ number_format($revNetSum, 2) }} €</span>
                </div>
                <div>
                    <p>Steuer</p>
                    <span>{{ number_format($revTaxSum, 2) }} €</span>
                </div>
                <div>
                    <p>Brutto</p>
                    <span>{{ number_format($revGrossSum, 2) }} €</span>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Rechnungsdatum:</th>
                        <th>Zahlungsdatum:</th>
                        <th>Firma:</th>
                        <th>Rechnungsnummer:</th>
                        <th>Netto:</th>
                        <th>Steuer:</th>
                        <th>Brutto:</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenues as $revenue)
                    <tr>
                        <td>{{ $revenue->billing_date }}</td>
                        <td>{{ $revenue->payment_date }}</td>
                        <td>{{ $revenue->company_name }}</td>
                        <td>{{ $revenue->invoice_number }}</td>
                        <td class="currency">{{ $revenue->net }}</td>
                        <td class="currency">{{ $revenue->tax }}</td>
                        <td class="currency">{{ $revenue->gross }}</td>
                        <td class="hover:bg-slate-600 cursor-pointer rounded-sm hover:text-slate-100 p-0!">
                            <a href="{{ route('revenue.edit', ['id' => $revenue->id]) }}">
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