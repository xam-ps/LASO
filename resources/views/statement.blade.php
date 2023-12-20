<x-app-layout>
    <x-slot name="header">
        <div id="dashboard_header" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <ul>
                @foreach ($years as $year)
                <li><a href="{{ route('statement.year', ['year' => $year]) }}">{{ $year }}</a></li>
                @endforeach
            </ul>
        </div>
    </x-slot>

    <div id="statement_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="revenue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                        <h1>Einnahme-Überschuss-Rechnung für {{$year}}</h1>
                        <tbody>
                            <tr>
                                <td colspan="3">
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
                                <td>{{Number::currency($revNetSum+$revTaxSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <h2>Ausgaben</h2>
                                </td>
                            </tr>
                            @foreach ($costs as $cost)
                            <tr>
                                <td>{{$cost->elster_id}}</td>
                                <td>{{$cost->full_name}}</td>
                                <td>{{$cost->total_cost}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>63</td>
                                <td>Gezahlte Vorsteuer</td>
                                <td>{{Number::currency($expTaxSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr>
                                <td>84</td>
                                <td>Fahrtkosten</td>
                                <td></td>
                            </tr>
                            <tr class="font-bold">
                                <td></td>
                                <td>Gesamt</td>
                                <td>{{Number::currency($revNetSum+$revTaxSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>