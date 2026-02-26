@extends('layouts.app')

@section('title', 'Visit History - ' . $patient->name)
@section('page-title', 'Visit History')
@section('breadcrumb', 'Patients / ' . $patient->name . ' / History')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('reception.patients.show', $patient) }}"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Patient Details
        </a>
    </div>

    {{-- Patient Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-user-injured"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $patient->name }}</h1>
                <p class="text-gray-500 text-sm flex items-center gap-3">
                    <span><i class="fas fa-id-badge mr-1"></i>{{ $patient->emrn ?? $patient->opd_number }}</span>
                    <span><i class="fas fa-phone mr-1"></i>{{ $patient->phone }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Visit History Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-blue-500"></i> Complete Visit History
            </h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold shadow-sm">
                Total Visits: {{ $visits->total() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Token / Visit ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Visit Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($visits as $visit)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-800">{{ $visit->created_at->format('d M, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $visit->created_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-700 font-mono">{{ $visit->queue_token }}</p>
                            <p class="text-xs text-gray-400">#{{ $visit->id }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border
                                {{ $visit->visit_type === 'emergency' ? 'bg-red-50 text-red-600 border-red-100' :
                                   ($visit->visit_type === 'routine' ? 'bg-blue-50 text-blue-600 border-blue-100' :
                                   'bg-teal-50 text-teal-600 border-teal-100') }}">
                                {{ ucfirst($visit->visit_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 font-medium">{{ $visit->doctor ? $visit->doctor->name : 'Unassigned' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                {{ $visit->status === 'completed' ? 'bg-green-100 text-green-700' :
                                   ($visit->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                   ($visit->status === 'cancelled' ? 'bg-gray-100 text-gray-500' :
                                   'bg-amber-100 text-amber-700')) }}">
                                {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('reception.visits.show', $visit) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors title='View Visit Details'">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-gray-50/20">
                            <i class="fas fa-history text-5xl mb-4 text-gray-200"></i>
                            <p class="text-lg">No visit history found for this patient.</p>
                            <a href="{{ route('reception.visits.create', ['patient_id' => $patient->id]) }}"
                               class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                                <i class="fas fa-plus"></i> New Visit
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $visits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
