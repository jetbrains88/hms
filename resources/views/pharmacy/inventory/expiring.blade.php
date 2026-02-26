@extends('layouts.app')

@section('title', 'Expiring Medicines')

@section('page-title', 'Expiring Soon')

@section('breadcrumb')
    <a href="{{ route('pharmacy.inventory') }}" class="text-indigo-600 hover:text-indigo-900">Inventory</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-600">Expiring Soon</span>
@endsection

@section('content')
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-amber-50/30">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Medicines Expiring within 90 Days</h3>
                <p class="text-sm text-slate-500">Monitor and manage stock that is close to expiry</p>
            </div>
            <div class="text-sm font-bold text-amber-600">
                Count: {{ $batches->total() }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Medicine</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Batch #</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800">{{ $batch->medicine->name }}</div>
                                <div class="text-xs text-slate-500">{{ $batch->medicine->generic_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $batch->batch_number }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $days = now()->diffInDays($batch->expiry_date, false);
                                @endphp
                                <div class="text-sm font-medium {{ $days <= 30 ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ $batch->expiry_date->format('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $days < 0 ? 'Expired' : 'In ' . $days . ' days' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700">{{ $batch->remaining_quantity }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($days < 0)
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-[10px] font-bold uppercase">Expired</span>
                                @elseif($days <= 30)
                                    <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold uppercase">Critical</span>
                                @else
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-[10px] font-bold uppercase">Warning</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('pharmacy.inventory.batch', $batch) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-check text-4xl text-slate-200 mb-3"></i>
                                    <p class="text-slate-500">No medicines expiring soon. Great job!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($batches->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
@endsection
