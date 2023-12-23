@props(['revenues', 'revNetSum', 'revTaxSum', 'revGrossSum', 'year'])

<div class="max-w-max mx-auto sm:px-6 lg:px-8">
    <div id="revenues" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
        <div class="flex justify-end mt-4 absolute right-4">
            <a href="{{ route('revenue.create') }}">
                <x-primary-button>
                    {{ __('+') }}
                </x-primary-button>
            </a>
        </div>
        <div class="px-6 py-4 text-gray-900 dark:text-gray-100">
            <h1>Einnahmen {{ $year }}</h1>
            <div class="total_amounts flex flex-row text-center">
                <div>
                    <p>Netto</p>
                    <span>{{Number::currency($revNetSum, in: 'EUR', locale: 'de')}}</span>
                </div>
                <div>
                    <p>Steuer</p>
                    <span>{{Number::currency($revTaxSum, in: 'EUR', locale: 'de')}}</span>
                </div>
                <div>
                    <p>Brutto</p>
                    <span>{{Number::currency($revGrossSum, in: 'EUR', locale: 'de')}}</span>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th></th>
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
                            <td class="p-0 hover:bg-slate-600 cursor-pointer rounded-sm hover:text-slate-100">
                                <a class="p-2 block" href="{{ route('revenue.edit', ['id' => $revenue->id]) }}">
                                    &#9998;
                                </a>
                            </td>
                            <td>{{ $revenue->billing_date }}</td>
                            <td>{{ $revenue->payment_date }}</td>
                            <td class="truncate max-w-xs">{{ $revenue->company_name }}</td>
                            <td>{{ $revenue->invoice_number }}</td>
                            <td class="currency">{{Number::currency($revenue->net, in: 'EUR', locale: 'de')}}</td>
                            <td class="currency">{{Number::currency($revenue->tax, in: 'EUR', locale: 'de')}}</td>
                            <td class="currency">{{Number::currency($revenue->gross, in: 'EUR', locale: 'de')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>