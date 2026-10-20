<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='vat-notice' />
    </x-slot>
    <div id="vat-notice_page" class="page-table-page py-5">
        <section id="vat-notices" class="page-table-panel table-panel" aria-labelledby="vat_title">
            <x-corner-add :href="route('vat-notice.createYear', ['year' => $year])" label="Umsatzsteuervoranmeldung hinzufügen" />
            <div class="table-panel-content text-gray-900 dark:text-gray-100">
                <div class="table-panel-header">
                    <h1 id="vat_title" class="table-panel-title">Umsatzsteuer {{ $year }}</h1>
                </div>
                <div class="vat-balance-columns">
                    <section class="vat-balance-group" aria-labelledby="vat-revenue-heading">
                        <h2 id="vat-revenue-heading">Umsatzsteuer aus Einnahmen</h2>
                        <div class="vat-balance-items">
                            <div class="vat-balance-item">
                                <p>Zu meldende steuerpflichtige Umsätze</p>
                                <span>{{ Number::currency($remainingNetRevenue, in: 'EUR', locale: 'de') }}</span>
                            </div>
                            <div class="vat-balance-item">
                                <p>Zu meldende Steuereinnahmen</p>
                                <span>{{ Number::currency($remainingRevenueTax, in: 'EUR', locale: 'de') }}</span>
                            </div>
                        </div>
                    </section>
                    <section class="vat-balance-group" aria-labelledby="vat-expense-heading">
                        <h2 id="vat-expense-heading">Vorsteuer aus Ausgaben</h2>
                        <div class="vat-balance-items">
                            <div class="vat-balance-item is-muted">
                                <p>Gezahlte Steuern Gesamt</p>
                                <span>{{ Number::currency($totalExpenseTax, in: 'EUR', locale: 'de') }}</span>
                            </div>
                            <div class="vat-balance-item">
                                <p>Zu meldende Steuerzahlungen</p>
                                <span>{{ Number::currency($remainingExpenseTax, in: 'EUR', locale: 'de') }}</span>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="table-viewport">
                    <table class="data-table">
                        <colgroup>
                            <col class="vat-action-column">
                            <col class="vat-amount-column">
                            <col class="vat-amount-column">
                            <col class="vat-date-column">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="table-action-heading"><span class="sr-only">Aktion</span></th>
                                <th class="text-right">Gemeldete Steuereinnahmen</th>
                                <th class="text-right">Gemeldete Steuerzahlungen</th>
                                <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vat_notices as $vat_notice)
                            <tr>
                                <td class="table-action-cell">
                                    <x-table-action :href="route('vat-notice.edit', ['id' => $vat_notice->id])"
                                        label="Voranmeldung bearbeiten" />
                                </td>
                                <td class="currency">{{ Number::currency($vat_notice->vat_received, in: 'EUR', locale: 'de') }}</td>
                                <td class="currency">{{ Number::currency($vat_notice->vat_paid, in: 'EUR', locale: 'de') }}</td>
                                <td>{{ $vat_notice->notice_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
