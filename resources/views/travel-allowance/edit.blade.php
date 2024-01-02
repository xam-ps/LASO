<x-app-layout>
    <div id="edit_travel-allowance_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Fahrt bearbeiten</h1>
                    <x-travel-form :$companyNames :$travelAllowance />
                    <form method="POST" action="{{ route('travel-allowance.delete', ['id'=>$travelAllowance->id]) }}"
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
    <script>
        function confirmSubmit() {
           return window.confirm("Sicher löschen?");
        }
    </script>
    @endsection

</x-app-layout>