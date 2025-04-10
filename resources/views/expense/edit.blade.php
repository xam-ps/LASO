<x-app-layout>
    <div id="edit_expense_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Ausgabe bearbeiten</h1>
                    <x-expense-form :$cost_types :$supplier_list :$expense :$now />
                    <form method="POST" action="{{ route('expense.delete', ['id'=>$expense->id]) }}"
                        onsubmit="return confirmSubmit()">
                        @method('DELETE')
                        @csrf
                        <x-delete-button class="mt-4 absolute right-10 bottom-8" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    @vite(['resources/js/grossCalculator.js'])
    @vite(['resources/js/toggleDepreciation.js'])
    @vite(['resources/js/costTypePopup.js'])
    <script>
        function confirmSubmit() {
            return window.confirm("Sicher löschen?");
        }
    </script>
    @endsection

</x-app-layout>