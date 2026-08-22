<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='statement' />
    </x-slot>

    <div id="statement_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-fit">
            <div id="revenue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg relative">
                <div class="flex justify-end mt-4 absolute right-6 text-4xl print:hidden">
                    <button type="button" onclick="window.print()" aria-label="Jahresabschluss drucken"
                        class="text-gray-700 dark:text-gray-300 fill-current stroke-current w-8 h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true">
                            <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path
                                d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Einnahme-Überschuss-Rechnung für {{$year}}</h1>

                    @if ($elster === null && $elsterFormPending)
                    <p id="elster_pending" class="my-2 text-sm text-gray-500 dark:text-gray-400">
                        Die Anlage EÜR {{ $year }} ist noch nicht veröffentlicht &ndash; ihre Zeilennummern
                        folgen in der Regel zu Beginn des Jahres {{ $year + 1 }}. Die Beträge dieser
                        Aufstellung sind davon nicht betroffen.
                    </p>
                    @elseif ($elster === null)
                    <x-warning-notice id="elster_notice" title="Keine Zeilennummern für {{ $year }} hinterlegt">
                        Die Beträge dieser Aufstellung sind vollständig, die Zeilennummern der Anlage EÜR
                        {{ $year }} sind in LASO aber noch nicht hinterlegt. Die Zuordnung bitte dem amtlichen
                        Vordruck entnehmen, bevor die Werte nach ELSTER übertragen werden.
                    </x-warning-notice>
                    @elseif (! $elster->confirmed)
                    <x-warning-notice id="elster_notice" title="Zeilennummern für {{ $year }} sind ungeprüft">
                        Alle Zeilennummern dieser Aufstellung stammen aus einem automatischen Abgleich mit
                        der ELSTER-Ausfüllhilfe und wurden noch nicht gegen den amtlichen Vordruck geprüft.
                        Bitte jede Zeilennummer prüfen, bevor die Werte nach ELSTER übertragen werden
                        &ndash; sonst landen Beträge in der falschen Zeile der Anlage EÜR.
                    </x-warning-notice>
                    @else
                    <p class="my-2 text-sm text-gray-500 dark:text-gray-400">
                        Zeilennummern nach Anlage EÜR {{ $elster->formYear }}.
                    </p>
                    @endif

                    <table>
                        {{-- visible column headers for screen readers. --}}
                        <caption class="sr-only">
                            Einnahme-Überschuss-Rechnung für {{ $year }}. Spalten: Zeile der Anlage EÜR,
                            Position, Betrag.
                        </caption>
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: left;">
                                    <h2>Einnahmen</h2>
                                </td>
                            </tr>
                            <tr>
                                <x-elster-line :$elster line-key="revenue_net" />
                                <td>Einnahmen Netto</td>
                                <td>{{Number::currency($revNetSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr>
                                <x-elster-line :$elster line-key="revenue_vat" />
                                <td>Einnahmen Ust</td>
                                <td>{{Number::currency($revTaxSum, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                            <tr>
                                <x-elster-line :$elster line-key="vat_refund" />
                                <td>Rückerstattung Ust</td>
                                <td>{{Number::currency($receivedVatPayments, in: 'EUR', locale: 'de')}}</td>
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
                                <x-elster-line :$elster :line-key="$cost->elster_key" />
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
                            <tr>
                                <td colspan="3" style="text-align: left;">
                                    <h2>Entnahmen und Einlagen</h2>
                                </td>
                            </tr>
                            <tr>
                                <x-elster-line :$elster line-key="entnahmen" />
                                <td>Entnahmen</td>
                                <td>- €</td>
                            </tr>
                            <tr>
                                <x-elster-line :$elster line-key="einlagen" />
                                <td>Nutzung privat PKW</td>
                                <td>{{Number::currency($travelAllowanceTotal, in: 'EUR', locale: 'de')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>