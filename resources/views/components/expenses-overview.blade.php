@props(['expenses', 'expNetSum', 'expTaxSum', 'expGrossSum', 'year'])

<section id="expenses" class="table-panel" aria-labelledby="expenses_title">
    <x-corner-add :href="route('expense.create')" label="Ausgabe hinzufügen" />
    <div class="table-panel-content text-gray-900 dark:text-gray-100">
        <div class="table-panel-header">
            <h1 id="expenses_title" class="table-panel-title">Ausgaben {{ $year }}</h1>
        </div>
        <div class="total_amounts">
            <div>
                <p>Netto</p>
                <span>{{ Number::currency($expNetSum, in: 'EUR', locale: 'de') }}</span>
            </div>
            <div>
                <p>Steuer</p>
                <span>{{ Number::currency($expTaxSum, in: 'EUR', locale: 'de') }}</span>
            </div>
            <div>
                <p>Brutto</p>
                <span>{{ Number::currency($expGrossSum, in: 'EUR', locale: 'de') }}</span>
            </div>
        </div>
        <div class="table-viewport" tabindex="0" aria-label="Ausgaben des Jahres {{ $year }}">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-action-heading"><span class="sr-only">Aktion</span></th>
                        <th>Art</th>
                        <th>Rechnungsdatum</th>
                        <th>Zahlungsdatum</th>
                        <th>Firma</th>
                        <th>Produkt</th>
                        <th>Rechnungsnummer</th>
                        <th class="text-right">Netto</th>
                        <th class="text-right">Steuer</th>
                        <th class="text-right">Brutto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr>
                        <td class="table-action-cell">
                            <x-table-action :href="route('expense.edit', ['id' => $expense->id])"
                                label="Ausgabe bearbeiten" />
                        </td>
                        <td>
                            <span class="cost-type-label">
                                <span class="cost-type-marker" style="--cost-color: #{{ $expense->costType->color_code }}"></span>
                                {{ $expense->costType->short_name }}
                            </span>
                        </td>
                        <td>{{ $expense->billing_date }}</td>
                        <td>{{ $expense->payment_date }}</td>
                        <td class="max-w-48 truncate" title="{{ $expense->supplier_name }}">{{ $expense->supplier_name }}</td>
                        <td class="max-w-48 truncate" title="{{ $expense->product_name }}">{{ $expense->product_name }}</td>
                        <td>{{ $expense->invoice_number }}</td>
                        <td class="currency">{{ Number::currency($expense->net, in: 'EUR', locale: 'de') }}</td>
                        <td class="currency">{{ Number::currency($expense->tax, in: 'EUR', locale: 'de') }}</td>
                        <td class="currency">{{ Number::currency($expense->gross, in: 'EUR', locale: 'de') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
