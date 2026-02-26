<x-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <h1 class="text-2xl font-bold text-red-700">🚨 EMERGENCY REGISTRATION</h1>
            <p class="text-red-600">Fast-track registration for critical patients</p>
        </div>
        
        <form action="{{ route('emergency.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Minimal Info -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Patient Name *</label>
                    <input type="text" name="name" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label>Age</label>
                    <input type="number" name="age" class="w-full border p-2 rounded">
                </div>
            </div>
            
            <!-- Triage Level -->
            <div>
                <label>Triage Level *</label>
                <select name="triage_level" required class="w-full border p-2 rounded">
                    <option value="red">🔴 Red - Immediate</option>
                    <option value="yellow">🟡 Yellow - Urgent</option>
                    <option value="green">🟢 Green - Delayed</option>
                </select>
            </div>
            
            <!-- Chief Complaint -->
            <div>
                <label>Chief Complaint *</label>
                <textarea name="complaint" rows="2" required class="w-full border p-2 rounded"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-lg font-bold hover:bg-red-700">
                CREATE EMERGENCY VISIT
            </button>
        </form>
    </div>
</x-layout>