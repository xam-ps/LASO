<x-app-layout>
    <x-slot name="header">
        <div id="dashboard_header" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <ul>
                @foreach ($years as $year_h)
                <li>
                    <a href="{{ route('travel-allowance.year', ['year' => $year_h]) }}">
                        {{ $year_h }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </x-slot>
    <div id="travel-allowance_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="travel-allowences"
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="flex justify-end mt-4 absolute right-4">
                    <a href="{{ route('travel-allowance.create') }}">
                        <x-primary-button>
                            {{ __('+') }}
                        </x-primary-button>
                    </a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Fahrten {{$year}}</h1>
                    <div class="total_amounts flex flex-row text-center">
                        <div>
                            <p>Gesamt</p>
                            <span>{{Number::currency($total, in: 'EUR', locale: 'de')}}</span>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Erstattung:</th>
                                    <th>Distanz:</th>
                                    <th>Datum:</th>
                                    <th>Start:</th>
                                    <th>Ende:</th>
                                    <th>Grund:</th>
                                    <th>Ziel:</th>
                                    <th>Firma:</th>
                                    <th>Notizen:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($travel_allowances as $travel_allowance)
                                <tr>
                                    <td class="p-0 hover:bg-slate-600 cursor-pointer rounded-sm hover:text-slate-100">
                                        <a class="p-2 block h-auto"
                                            href="{{ route('travel-allowance.edit', ['id' => $travel_allowance->id]) }}">
                                            &#9998;
                                        </a>
                                    </td>
                                    <td class="currency">{{Number::currency($travel_allowance->refund, in: 'EUR',
                                        locale:
                                        'de')}}</td>
                                    <td>{{ $travel_allowance->distance }} km</td>
                                    <td>{{ $travel_allowance->travel_date }}</td>
                                    <td>{{ $travel_allowance->start }}</td>
                                    <td>{{ $travel_allowance->end }}</td>
                                    <td class="truncate max-w-xs">{{ $travel_allowance->reason }}</td>
                                    <td>{{ $travel_allowance->destination }}</td>
                                    <td class="truncate max-w-xs">{{ $travel_allowance->company }}</td>
                                    <td>{{ $travel_allowance->notes }}</td>
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