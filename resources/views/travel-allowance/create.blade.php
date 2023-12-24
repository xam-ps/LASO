<x-app-layout>
    <div id="create_travel-allowance_page" class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Neue Fahrt anlegen</h1>
                    <form method="post" action="{{ route('travel-allowance.store') }}" class="my-2">
                        @csrf
                        <label for="travel_date">Datum:</label><br>
                        <input type="date" id="travel_date" name="travel_date"
                            value="{{ old('travel_date', $now) }}"><br>

                        <label for="start">Start:</label><br>
                        <input type="time" id="start" name="start" value="{{ old('start', '14:00') }}"><br>

                        <label for="end">End:</label><br>
                        <input type="time" id="end" name="end" value="{{ old('end', '14:00') }}"><br>

                        <label for="destination">Zielort:</label><br>
                        <input id="destination" name="destination" value="{{ old('destination') }}"><br>

                        <label for="reason">Grund:</label><br>
                        <input id="reason" name="reason" value="{{ old('reason') }}"><br>

                        <label for="company_name">Kunde (optional):</label><br>
                        <input id="company_name" name="company_name" list="companies"
                            value="{{ old('company_name') }}"><br>
                        <datalist id="companies">
                            @foreach ($companyNames as $companyName)
                            <option>{{$companyName}}</option>
                            @endforeach
                        </datalist>

                        <label for="distance">Distanz:</label><br>
                        <input id="distance" name="distance" type="number" value="{{ old('distance') }}" min="0"
                            step="1"> km<br>

                        <label for="notes">Notizen (optional):</label><br>
                        <input id="notes" name="notes" value="{{ old('notes') }}"><br>

                        <label for="refund">Erstattung:</label><br>
                        <input id="refund" name="refund" type="number" value="{{ old('refund') }}" min="0" step="0.01">
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
    </script>
    @endsection

</x-app-layout>