<x-app-layout>
    <div id="edit_travel-allowance_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Fahrt bearbeiten</h1>
                    <form method="POST" action="{{ route('travel-allowance.update', ['id'=>$travelAllowance->id]) }}"
                        class="my-2">
                        @method('PUT')
                        @csrf
                        <label for="travel_date">Datum:</label><br>
                        <input type="date" id="travel_date" name="travel_date"
                            value="{{ $travelAllowance->travel_date }}"><br>

                        <label for="start">Start:</label><br>
                        <input type="time" id="start" name="start"
                            value="{{ \Carbon\Carbon::parse($travelAllowance->start)->format('H:i') }}"><br>

                        <label for="end">End:</label><br>
                        <input type="time" id="end" name="end"
                            value="{{ \Carbon\Carbon::parse($travelAllowance->end)->format('H:i') }}"><br>

                        <label for="destination">Ziel:</label><br>
                        <input id="destination" name="destination" value="{{ $travelAllowance->destination }}"><br>

                        <label for="reason">Grund:</label><br>
                        <input id="reason" name="reason" value="{{ $travelAllowance->reason }}"><br>

                        <label for="company_name">Kunde:</label><br>
                        <input id="company_name" name="company_name" list="companies"
                            value="{{ $travelAllowance->company }}"><br>
                        <datalist id="companies">
                            @foreach ($companyNames as $companyName)
                            <option>{{ $companyName }}</option>
                            @endforeach
                        </datalist>

                        <label for="distance">Distanz:</label><br>
                        <input id="distance" name="distance" type="number" value="{{ $travelAllowance->distance }}"
                            min="0" step="1"> km<br>

                        <label for="notes">Notizen:</label><br>
                        <input id="notes" name="notes" value="{{ $travelAllowance->notes }}"><br>

                        <label for="refund">Erstattung:</label><br>
                        <input id="refund" name="refund" type="number" value="{{ $travelAllowance->refund }}" min="0"
                            step="0.01">
                        €<br>

                        @if ($errors->any())
                        <div class="text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="text-center">
                            <x-primary-button class="mt-4">
                                {{ __('Speichern') }}
                            </x-primary-button>
                        </div>
                    </form>

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
        document.addEventListener('DOMContentLoaded', function () {
            const distance = document.querySelector("#distance");
            
            distance.addEventListener("keyup", calcRefund);
            distance.addEventListener("change", calcRefund);

            function calcRefund(){
                $oneWay = distance.value/2;
                if ($oneWay <= 20){
                    refund.value = (parseFloat(distance.value)*0.30).toFixed(2);
                } else {
                    refund.value = ((parseFloat(20)*0.30 + (parseFloat(distance.value/2)-20)*0.38)*2).toFixed(2);
                }
            }
        });
        function confirmSubmit() {
           return window.confirm("Sicher löschen?");
        }
    </script>
    @endsection

</x-app-layout>