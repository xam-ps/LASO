<x-app-layout>
    <div id="assets_page" class="py-12">
        <div class="max-w-max mx-auto sm:px-6 lg:px-8 mt-4">
            <div id="assets" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Anlagenverzeichnis</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Zahlungsdatum:</th>
                                <th>Produkt:</th>
                                <th>Nutzungsdauer:</th>
                                <th>1. Jahr:</th>
                                <th>Regulär:</th>
                                <th>Letztes Jahr:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expensesWithTypeAfa as $expense)
                            <tr>
                                <td>{{ $expense->payment_date }}</td>
                                <td>{{ $expense->product_name }}</td>
                                <td>{{ $expense->depreciation }}</td>
                                <td class="currency">{{Number::currency($expense->net / $expense->depreciation *
                                    (12 - (\Carbon\Carbon::parse($expense->payment_date)->month - 1)) / 12, in: 'EUR',
                                    locale: 'de')}}</td>
                                <td class="currency">{{Number::currency($expense->net/$expense->depreciation, in: 'EUR',
                                    locale: 'de')}}</td>
                                <td class="currency">{{Number::currency($expense->net / $expense->depreciation *
                                    ((\Carbon\Carbon::parse($expense->payment_date)->month-1) / 12), in: 'EUR', locale:
                                    'de')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>