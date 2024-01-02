<x-app-layout>
    <div id="create_expense_page" class="py-12 z-0">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Neue Ausgabe anlegen</h1>
                    <x-expense-form :$cost_types :$supplier_list :$now />
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    @vite(['resources/js/grossCalculator.js'])
    @vite(['resources/js/toggleDepreciation.js'])
    @vite(['resources/js/costTypePopup.js'])
    @endsection

</x-app-layout>