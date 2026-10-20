@props(['revenues', 'revNetSum', 'revTaxSum', 'revGrossSum', 'year'])

<section id="revenues" class="table-panel" aria-labelledby="revenues_title">
    <x-corner-add :href="route('revenue.create')" label="Einnahme hinzufügen" />
    <div class="table-panel-content text-gray-900 dark:text-gray-100">
        <div class="table-panel-header">
            <h1 id="revenues_title" class="table-panel-title">Einnahmen {{ $year }}</h1>
        </div>
        <div class="total_amounts">
            <div>
                <p>Netto</p>
                <span>{{ Number::currency($revNetSum, in: 'EUR', locale: 'de') }}</span>
            </div>
            <div>
                <p>Steuer</p>
                <span>{{ Number::currency($revTaxSum, in: 'EUR', locale: 'de') }}</span>
            </div>
            <div>
                <p>Brutto</p>
                <span>{{ Number::currency($revGrossSum, in: 'EUR', locale: 'de') }}</span>
            </div>
        </div>
        <div class="table-viewport" tabindex="0" aria-label="Einnahmen des Jahres {{ $year }}">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-action-heading"><span class="sr-only">Aktion</span></th>
                        <th>Rechnungsdatum</th>
                        <th>Zahlungsdatum</th>
                        <th>Firma</th>
                        <th>Rechnungsnummer</th>
                        <th class="text-right">Netto</th>
                        <th class="text-right">Steuer</th>
                        <th class="text-right">Brutto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenues as $revenue)
                    <tr>
                        <td class="table-action-cell">
                            <x-table-action :href="route('revenue.edit', ['id' => $revenue->id])"
                                label="Einnahme bearbeiten" />
                        </td>
                        <td>{{ $revenue->billing_date }}</td>
                        <td>{{ $revenue->payment_date }}</td>
                        <td class="max-w-56 truncate" title="{{ $revenue->company_name }}">{{ $revenue->company_name }}</td>
                        <td>{{ $revenue->invoice_number }}</td>
                        <td class="currency">{{ Number::currency($revenue->net, in: 'EUR', locale: 'de') }}</td>
                        <td class="currency">{{ Number::currency($revenue->tax, in: 'EUR', locale: 'de') }}</td>
                        <td class="currency">{{ Number::currency($revenue->gross, in: 'EUR', locale: 'de') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
