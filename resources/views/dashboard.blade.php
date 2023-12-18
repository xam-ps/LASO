<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div id="dashboard_page" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="revenue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="flex justify-end mt-4 absolute right-4">
                    <a href="{{route('revenue.create')}}">
                        <x-primary-button>
                            {{ __('+') }}
                        </x-primary-button>
                    </a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Revenues</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Rechnungsdatum:</th>
                                <th>Zahlungsdatum:</th>
                                <th>Firma:</th>
                                <th>Rechnungsnummer:</th>
                                <th>Netto:</th>
                                <th>Steuer:</th>
                                <th>Brutto:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($revenues as $revenue)
                            <tr>
                                <td>{{$revenue->billing_date}}</td>
                                <td>{{$revenue->payment_date}}</td>
                                <td>{{$revenue->company_name}}</td>
                                <td>{{$revenue->invoice_number}}</td>
                                <td>{{$revenue->net}} €</td>
                                <td>{{$revenue->tax}} €</td>
                                <td>{{$revenue->gross}} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>