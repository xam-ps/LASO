<x-app-layout>
    <div id="create_revenue_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Neue Einnahme anlegen</h1>
                    <x-revenue-form :$customer_list :$now />
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    @vite(['resources/js/grossCalculator.js'])
    @endsection

</x-app-layout>