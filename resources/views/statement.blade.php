<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='statement' />
    </x-slot>

    <div id="statement_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-fit">
            <div id="revenue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
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
                            <tr>
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