<x-app-layout>
    <div id="assets_page" class="page-table-page py-5">
        <section id="assets" class="page-table-panel table-panel" aria-labelledby="assets_title">
            <div class="table-panel-content text-gray-900 dark:text-gray-100">
                <div class="table-panel-header">
                    <h1 id="assets_title" class="table-panel-title">Anlagenverzeichnis</h1>
                </div>
                <div class="table-viewport">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Zahlungsdatum</th>
                                <th>Produkt</th>
                                <th>Nutzungsdauer</th>
                                <th class="text-right">Einlagewert</th>
                                <th class="text-right">1. Jahr</th>
                                <th class="text-right">Regulär</th>
                                <th class="text-right">Letztes Jahr</th>
                                <th class="text-right">Restwert</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expensesWithTypeAfa as $expense)
                            @if ($expense->yearsInUse >= $expense->depreciation)
                            @once
                            <tr class="table-section-row">
                                <td colspan="8">Abgeschriebene Anlagen</td>
                            </tr>
                            @endonce
                            @endif
                            <tr>
                                <td>{{ $expense->payment_date }}</td>
                                <td class="max-w-56 truncate" title="{{ $expense->product_name }}">{{ $expense->product_name }}</td>
                                <td>
                                    <div class="depreciation-progress" role="progressbar"
                                        aria-label="Nutzungsdauer {{ $expense->depreciation }} Jahre"
                                        aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ min(100, $expense->percUsed) }}">
                                        <span style="width: {{ $expense->percUsed }}%"></span>
                                        <strong>{{ $expense->depreciation }} Jahre</strong>
                                    </div>
                                </td>
                                <td class="currency">{{ Number::currency($expense->net, in: 'EUR', locale: 'de') }}</td>
                                <td class="currency">{{ Number::currency($expense->firstYear, in: 'EUR', locale: 'de') }}</td>
                                <td class="currency">{{ Number::currency($expense->middleYear, in: 'EUR', locale: 'de') }}</td>
                                <td class="currency">{{ Number::currency($expense->lastYear, in: 'EUR', locale: 'de') }}</td>
                                <td class="currency">{{ Number::currency($expense->residualValue, in: 'EUR', locale: 'de') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
