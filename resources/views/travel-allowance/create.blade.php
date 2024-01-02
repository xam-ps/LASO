<x-app-layout>
    <div id="create_travel-allowance_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Neue Fahrt anlegen</h1>
                    <x-travel-form :$now :$companyNames />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>