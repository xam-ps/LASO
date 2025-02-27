<x-app-layout>
    <div id="create_vat-notice_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Neue Umsatzsteuer&shy;voranmeldung</h1>
                    <x-vat-notice-form :$now :$remainingRevenueTax :$remainingExpenseTax :$year />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>