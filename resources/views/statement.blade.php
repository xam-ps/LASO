<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='statement' />
    </x-slot>

    <div id="statement_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-fit">
            <div id="revenue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="flex justify-end mt-4 absolute right-6 text-4xl print:hidden" onclick="window.print()">
                    <button class="text-gray-700 dark:text-gray-300 fill-current stroke-current w-8 h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path
                                d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                        <h1>Einnahme-Überschuss-Rechnung für {{$year}}</h1>
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: left;">
                                    <h2>Einnahmen</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Einnahmen Netto</td>
                                <td>{{Number::currency($revNetSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Einnahmen Ust</td>
                                <td>{{Number::currency($revTaxSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Rückerstattung Ust</td>
                                <td> - </td>
                            </tr>
                            <tr class="font-bold">
                                <td></td>
                                <td>Gesamt</td>
                                <td>{{Number::currency($revTotal, in: 'EUR', locale: 'de')}}</td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                    <h2>Ausgaben</h2>
                                </td>
                            </tr>
                            @foreach ($costs as $cost)
                            <tr title="{{$cost->description}}">
                                <td>{{$cost->elster_id}}</td>
                                <td>{{$cost->full_name}}</td>
                                <td>
                                    {{Number::currency($cost->total_net, in: 'EUR', locale: 'de')}}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>Gesamtausgaben</td>
                                <td>{{Number::currency($expTotal, in: 'EUR', locale:
                                    'de')}}</td>
                            </tr>
                            <tr class="font-bold">
                                <td></td>
                                <td>Jahresergebnis</td>
                                <td>{{Number::currency($profit, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr></tr>
                            <tr>
                                <td colspan="3" style="text-align: left;">
                                    <h2>Entnahmen und Einlagen</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>106</td>
                                <td>Entnahmen</td>
                                <td>- €</td>
                            </tr>
                            <tr>
                                <td>107</td>
                                <td>Nutzung privat PKW</td>
                                <td>{{Number::currency($travelAllowanceTotal, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>