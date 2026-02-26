@extends('layouts.app')

@section('title', 'Edit Patient - ' . $patient->name)
@section('page-title', 'Edit Patient')
@section('breadcrumb', 'Patients / Edit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-edit text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Patient</h1>
                    <p class="text-amber-100 text-sm mt-1">{{ $patient->name }} — {{ $patient->emrn ?? $patient->opd_number }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('reception.patients.update', $patient) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                        <span class="font-semibold text-red-800">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Basic Information --}}
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-500"></i> Basic Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none @error('name') border-red-400 @enderror" required>
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none @error('phone') border-red-400 @enderror" required>
                        @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : '') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none" required>
                            <option value="">Select Gender</option>
                            @foreach(['male', 'female', 'other'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $patient->gender) === $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                        <input type="text" name="cnic" value="{{ old('cnic', $patient->cnic) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                            placeholder="00000-0000000-0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Group</label>
                        <select name="blood_group"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $group)
                                <option value="{{ $group }}" {{ old('blood_group', $patient->blood_group) === $group ? 'selected' : '' }}>{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">{{ old('address', $patient->address) }}</textarea>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Patient Type --}}
            <div x-data="{ isNhmp: {{ $patient->is_nhmp ? 'true' : 'false' }} }">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-id-badge text-purple-500"></i> Patient Type
                </h2>
                <div class="flex items-center gap-3 mb-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_nhmp" value="1" x-model="isNhmp"
                            class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                            {{ $patient->is_nhmp ? 'checked' : '' }}>
                        <span class="font-medium text-gray-700">NHMP Staff / Personnel</span>
                    </label>
                </div>
                <div x-show="isNhmp" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-purple-50 p-4 rounded-xl border border-purple-200">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                        <select name="designation_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}"
                                    {{ old('designation_id', optional($patient->employeeDetail)->designation_id) == $designation->id ? 'selected' : '' }}>
                                    {{ $designation->name }} (BPS-{{ $designation->bps }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Office / Unit</label>
                        <select name="office_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                            <option value="">Select Office</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}"
                                    {{ old('office_id', optional($patient->employeeDetail)->office_id) == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rank / Batch No.</label>
                        <input type="text" name="rank" value="{{ old('rank', $patient->rank) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                            placeholder="e.g. Inspector, 5432">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Medical Information --}}
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-notes-medical text-green-500"></i> Medical Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Known Allergies</label>
                        <textarea name="allergies" rows="2"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">{{ old('allergies', $patient->allergies) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chronic Conditions</label>
                        <textarea name="chronic_conditions" rows="2"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">{{ old('chronic_conditions', $patient->chronic_conditions) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medical History</label>
                        <textarea name="medical_history" rows="3"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('reception.patients.show', $patient) }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
