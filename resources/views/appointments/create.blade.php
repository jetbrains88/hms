@extends('layouts.app')

@section('title', 'Schedule Appointment')

@section('page-title', 'Schedule New Appointment')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form method="POST" action="{{ route('appointments.store') }}" x-data="appointmentForm()">
            @csrf
            
            <div class="space-y-6">
                <!-- Patient Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Patient *</label>
                    <select name="patient_id" required class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $patientId == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->emrn }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Doctor Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Doctor *</label>
                    <select name="doctor_id" 
                            x-model="doctorId"
                            @change="loadAvailableSlots()"
                            required 
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $doctorId == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date *</label>
                    <input type="date" 
                           x-model="date"
                           @change="loadAvailableSlots()"
                           name="scheduled_at" 
                           min="{{ now()->format('Y-m-d') }}"
                           required
                           class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Available Time Slots -->
                <div x-show="slots.length > 0" x-cloak>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Available Time Slots</label>
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="slot in slots" :key="slot">
                            <button type="button"
                                    @click="selectedTime = slot"
                                    class="p-2 text-sm border rounded-lg hover:bg-blue-50 hover:border-blue-500 transition-colors"
                                    :class="{'bg-blue-500 text-white hover:bg-blue-600': selectedTime === slot}">
                                <span x-text="slot"></span>
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="scheduled_at" x-model="datetimeValue">
                </div>
                
                <!-- Appointment Type -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Appointment Type *</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="type" value="physical" checked class="mr-2">
                            <i class="fas fa-hospital mr-1 text-green-500"></i>Physical
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="online" class="mr-2">
                            <i class="fas fa-video mr-1 text-purple-500"></i>Online
                        </label>
                    </div>
                </div>
                
                <!-- Reason -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Reason (Optional)</label>
                    <textarea name="reason" 
                              rows="3"
                              class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Brief reason for visit..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('appointments.index') }}" 
                       class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">
                        Schedule Appointment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function appointmentForm() {
    return {
        doctorId: '{{ $doctorId }}',
        date: '',
        slots: [],
        selectedTime: '',
        
        async loadAvailableSlots() {
            if (!this.doctorId || !this.date) return;
            
            this.slots = [];
            this.selectedTime = '';
            
            try {
                const response = await fetch(`/api/appointments/available-slots?doctor_id=${this.doctorId}&date=${this.date}`);
                this.slots = await response.json();
            } catch (error) {
                console.error('Failed to load slots:', error);
            }
        },
        
        get datetimeValue() {
            return this.date && this.selectedTime ? `${this.date} ${this.selectedTime}:00` : '';
        }
    }
}
</script>
@endsection