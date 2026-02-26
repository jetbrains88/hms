<x-layout>
    <div class="max-w-4xl mx-auto py-6">
        <form action="{{ route('vitals.store') }}" method="POST" class="bg-white shadow-xl rounded-lg overflow-hidden border">
            @csrf
            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
            <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">

            <div class="bg-indigo-700 p-4 text-white flex justify-between items-center">
                <h2 class="text-xl font-bold">Vitals Check: {{ $visit->patient->name }}</h2>
                <span class="bg-indigo-500 px-3 py-1 rounded text-sm">Token: {{ $visit->queue_token }}</span>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700">Temp (°F)</label>
                    <input type="number" step="0.1" name="temperature" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Pulse (BPM)</label>
                    <input type="number" name="pulse" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Resp. Rate</label>
                    <input type="number" name="respiratory_rate" required class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">BP (Systolic)</label>
                    <input type="number" name="blood_pressure_systolic" required class="w-full border rounded p-2" placeholder="120">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">BP (Diastolic)</label>
                    <input type="number" name="blood_pressure_diastolic" required class="w-full border rounded p-2" placeholder="80">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Oxygen (SpO2 %)</label>
                    <input type="number" name="oxygen_saturation" required class="w-full border rounded p-2" placeholder="98">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Height (cm)</label>
                    <input type="number" step="0.1" name="height" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Pain Scale (0-10)</label>
                    <select name="pain_scale" class="w-full border rounded p-2">
                        @for($i=0; $i<=10; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                    </select>
                </div>
            </div>

            <div class="px-6 pb-6">
                <label class="block text-sm font-bold text-gray-700">Staff Notes</label>
                <textarea name="notes" rows="2" class="w-full border rounded p-2"></textarea>
                <button type="submit" class="mt-4 w-full bg-indigo-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-indigo-800 transition">
                    Submit Vitals to Doctor
                </button>
            </div>
        </form>
    </div>
</x-layout>