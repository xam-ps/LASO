<x-app-layout>
    <x-slot name="header">
        <x-year-nav :$year :$years location='travel-allowance' />
    </x-slot>
    <div id="travel-allowance_page" class="page-table-page py-5">
        <section id="travel-allowences" class="page-table-panel table-panel" aria-labelledby="travel_title">
            <x-corner-add :href="route('travel-allowance.create')" label="Fahrt hinzufügen" />
            <div class="table-panel-content text-gray-900 dark:text-gray-100">
                <div class="table-panel-header">
                    <h1 id="travel_title" class="table-panel-title">Fahrten {{ $year }}</h1>
                </div>
                <div class="total_amounts two-values">
                    <div>
                        <p>Gesamt</p>
                        <span>{{ Number::currency($total, in: 'EUR', locale: 'de') }}</span>
                    </div>
                    <div>
                        <p>KM</p>
                        <span>{{ $totalDistance }} km</span>
                    </div>
                </div>
                <div class="table-viewport">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th colspan="2" class="table-action-heading"><span class="sr-only">Bearbeiten und Übernehmen</span></th>
                                <th class="text-right">Erstattung</th>
                                <th>Distanz</th>
                                <th>Datum</th>
                                <th>Start</th>
                                <th>Ende</th>
                                <th>Grund</th>
                                <th>Ziel</th>
                                <th>Firma</th>
                                <th>Notizen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($travel_allowances as $travel_allowance)
                            <tr>
                                <td class="table-action-cell">
                                    <x-table-action :href="route('travel-allowance.edit', ['id' => $travel_allowance->id])"
                                        label="Fahrt bearbeiten" />
                                </td>
                                <td class="table-action-cell">
                                    <x-table-action :href="route('travel-allowance.copy', ['travelAllowance' => $travel_allowance->id])"
                                        label="Als neue Fahrt übernehmen: {{ $travel_allowance->destination }}" icon="copy" />
                                </td>
                                <td class="currency">{{ Number::currency($travel_allowance->refund, in: 'EUR', locale: 'de') }}</td>
                                <td>{{ $travel_allowance->distance }} km</td>
                                <td>{{ $travel_allowance->travel_date }}</td>
                                <td>{{ $travel_allowance->start }}</td>
                                <td>{{ $travel_allowance->end }}</td>
                                <td class="max-w-56 truncate" title="{{ $travel_allowance->reason }}">{{ $travel_allowance->reason }}</td>
                                <td>{{ $travel_allowance->destination }}</td>
                                <td class="max-w-48 truncate" title="{{ $travel_allowance->company }}">{{ $travel_allowance->company }}</td>
                                <td class="max-w-56 truncate" title="{{ $travel_allowance->notes }}">{{ $travel_allowance->notes }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
