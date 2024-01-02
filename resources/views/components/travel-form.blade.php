@props(['now', 'companyNames', 'travelAllowance'])

@if (isset($travelAllowance))
<form class="my-2" method="post" action="{{ route('travel-allowance.update', ['id'=>$travelAllowance->id]) }}">
    @method('PUT')
    @else
    <form class="my-2" method="post" action="{{ route('travel-allowance.store') }}">
        @endif
        @csrf
        <label for="travel_date">Datum:</label><br>
        @error('travel_date')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="date" id="travel_date" name="travel_date"
            value="{{ old('travel_date', $travelAllowance->travel_date ?? $now) }}"><br>

        <label for="start">Start:</label><br>
        @error('start')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="time" id="start" name="start"
            value="{{ old('start', \Carbon\Carbon::parse($travelAllowance->start ?? '14:00')->format('H:i')) }}"><br>

        <label for="end">End:</label><br>
        @error('end')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="time" id="end" name="end"
            value="{{ old('end', \Carbon\Carbon::parse($travelAllowance->end ?? '14:00')->format('H:i')) }}"><br>

        <label for="destination">Zielort:</label><br>
        @error('destination')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="destination" name="destination"
            value="{{ old('destination', $travelAllowance->destination ?? '') }}"><br>

        <label for="reason">Grund:</label><br>
        @error('reason')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="reason" name="reason" value="{{ old('reason', $travelAllowance->reason ?? '') }}"><br>

        <label for="company_name">Kunde (optional):</label><br>
        @error('company_name')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="company_name" name="company_name" list="companies"
            value="{{ old('company_name', $travelAllowance->company ?? '') }}"><br>
        <datalist id="companies">
            @foreach ($companyNames as $companyName)
            <option>{{$companyName}}</option>
            @endforeach
        </datalist>

        <label for="distance">Distanz:</label><br>
        @error('distance')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="distance" name="distance" type="number"
            value="{{ old('distance', $travelAllowance->distance ?? '') }}" min="0" step="1"> km<br>

        <label for="notes">Notizen (optional):</label><br>
        @error('notes')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="notes" name="notes" value="{{ old('notes', $travelAllowance->notes ?? '') }}"><br>

        <label for="refund">Erstattung:</label><br>
        @error('refund')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input id="refund" name="refund" type="number" value="{{ old('refund', $travelAllowance->refund ?? '') }}"
            min="0" step="0.01">
        €<br>

        <div class="text-center">
            <x-primary-button class="mt-4">
                {{ __('Speichern') }}
            </x-primary-button>
        </div>
    </form>

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