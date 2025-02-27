<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='vat-notice' />
    </x-slot>
    <div id="vat-notice_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="vat-notices" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg relative">
                <div class="flex justify-end mt-4 absolute right-4">
                    <a href="{{ route('vat-notice.createYear', ['year' => $year]) }}">
                        <x-primary-button>
                            {{ __('+') }}
                        </x-primary-button>
                    </a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Umsatzsteuer {{$year}}</h1>
                    <div class="total_amounts flex flex-row text-center my-4">
                        <div>
                            <p>Steuereinnahmen Gesamt</p>
                            <span>{{Number::currency($totalRevenueTax, in: 'EUR', locale: 'de')}}</span>
                        </div>
                        <div>
                            <p>Gezahlte Steuern Gesamt</p>
                            <span>{{Number::currency($totalExpenseTax, in: 'EUR', locale: 'de')}}</span>
                        </div>
                    </div>
                    <div class="total_amounts flex flex-row text-center">
                        <div>
                            <p>Zu meldende Steuereinnahmen</p>
                            <span>{{Number::currency($remainingRevenueTax, in: 'EUR', locale: 'de')}}</span>
                        </div>
                        <div>
                            <p>Zu meldende Steuerzahlungen</p>
                            <span>{{Number::currency($remainingExpenseTax, in: 'EUR', locale: 'de')}}</span>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="editable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Gemeldete Steuereinnahmen:</th>
                                    <th>Gemeldete Steuerzahlungen:</th>
                                    <th>Datum:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vat_notices as $vat_notice)
                                <tr>
                                    <td class="p-0 hover:bg-slate-600 cursor-pointer rounded-xs hover:text-slate-100">
                                        <a class="block h-auto"
                                            href="{{ route('vat-notice.edit', ['id' => $vat_notice->id]) }}">
                                            &#9998;
                                        </a>
                                    </td>
                                    <td class="currency">{{Number::currency($vat_notice->vat_received, in: 'EUR',
                                        locale:
                                        'de')}}</td>
                                    <td class="currency">{{Number::currency($vat_notice->vat_paid, in: 'EUR',
                                        locale:
                                        'de')}}</td>
                                    <td>{{ $vat_notice->notice_date }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>