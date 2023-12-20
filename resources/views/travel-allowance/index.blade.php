<x-app-layout>
    <div id="create_expense_page" class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div id="revenues" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="flex justify-end mt-4 absolute right-4">
                    <a href="{{ route('travel-allowance.create') }}">
                        <x-primary-button>
                            {{ __('+') }}
                        </x-primary-button>
                    </a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Fahrten</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Datum:</th>
                                <th>Start:</th>
                                <th>Ende:</th>
                                <th>Ziel:</th>
                                <th>Grund:</th>
                                <th>Firma:</th>
                                <th>Distanz:</th>
                                <th>Notizen:</th>
                                <th>Erstattung:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($travel_allowances as $travel_allowance)
                            <tr>
                                <td>{{ $travel_allowance->travel_date }}</td>
                                <td>{{ $travel_allowance->start }}</td>
                                <td>{{ $travel_allowance->end }}</td>
                                <td>{{ $travel_allowance->destination }}</td>
                                <td>{{ $travel_allowance->reason }}</td>
                                <td>{{ $travel_allowance->company }}</td>
                                <td>{{ $travel_allowance->distance }} km</td>
                                <td>{{ $travel_allowance->notes }}</td>
                                <td class="currency">{{Number::currency($travel_allowance->refund, in: 'EUR', locale:
                                    'de')}}</td>
                                <td class="hover:bg-slate-600 cursor-pointer rounded-sm hover:text-slate-100 p-0!">
                                    <a href="{{ route('revenue.edit', ['id' => $travel_allowance->id]) }}">
                                        &#9998;
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>