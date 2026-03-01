@extends('layouts.app')

@section('title', 'Medicine Details - ' . $medicine->name)
@section('page-title', 'Medicine Details')
@section('breadcrumb', 'Pharmacy / Medicines / ' . $medicine->name)

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-pills text-3xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-800 leading-tight">{{ $medicine->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider">{{ $medicine->generic_name }}</span>
                    @if($medicine->is_global)
                        <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-xs font-bold uppercase tracking-wider">Global</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pharmacy.medicines.edit', $medicine) }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm">
                <i class="fas fa-edit"></i> Edit Medicine
            </a>
            <a href="{{ route('pharmacy.inventory.create', ['medicine_id' => $medicine->id]) }}" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-bold hover:shadow-lg transition-all shadow-blue-200/50">
                <i class="fas fa-plus"></i> Add Stock
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Stats Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full translate-x-16 -translate-y-16 opacity-50"></div>
                
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 bg-slate-50 w-fit px-3 py-1 rounded-full">Stock Overview</h3>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-4xl font-black text-slate-800">{{ $totalStock }}</span>
                            <span class="text-slate-500 font-bold uppercase tracking-wider text-xs">{{ $medicine->unit }}</span>
                        </div>
                        <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                            @php
                                $percent = $medicine->reorder_level > 0 ? min(100, ($totalStock / ($medicine->reorder_level * 2)) * 100) : 100;
                                $color = $totalStock <= $medicine->reorder_level ? 'from-rose-500 to-rose-600' : 'from-emerald-400 to-emerald-600';
                            @endphp
                            <div class="h-full bg-gradient-to-r {{ $color }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reorder Level</p>
                            <p class="text-lg font-bold text-slate-700">{{ $medicine->reorder_level }} <span class="text-xs">{{ $medicine->unit }}</span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</p>
                            @if($totalStock <= 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 text-xs font-black uppercase tracking-wider">Out of Stock</span>
                            @elseif($totalStock <= $medicine->reorder_level)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 text-xs font-black uppercase tracking-wider">Low Stock</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-black uppercase tracking-wider">Healthy</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Medicine Specifications</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-slate-50">
                        <span class="text-sm text-slate-500 font-medium">Category</span>
                        <span class="text-sm text-slate-800 font-bold">{{ $medicine->category->name ?? 'Uncategorized' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-50">
                        <span class="text-sm text-slate-500 font-medium">Form</span>
                        <span class="text-sm text-slate-800 font-bold">{{ $medicine->form->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-50">
                        <span class="text-sm text-slate-500 font-medium">Strength</span>
                        <span class="text-sm text-slate-800 font-bold">{{ $medicine->strength_value }} {{ $medicine->strength_unit }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-50">
                        <span class="text-sm text-slate-500 font-medium">Manufacturer</span>
                        <span class="text-sm text-slate-800 font-bold">{{ $medicine->manufacturer ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-sm text-slate-500 font-medium">Prescription Required</span>
                        @if($medicine->requires_prescription)
                            <span class="px-2 py-1 rounded-lg bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-wider">Required</span>
                        @else
                            <span class="px-2 py-1 rounded-lg bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory List (Batches) -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex-1 flex flex-col overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Active Stock Batches</h3>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-black">Tracking individual batch expirations</p>
                    </div>
                    <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                <th class="px-8 py-4">Batch Details</th>
                                <th class="px-8 py-4">Quantity</th>
                                <th class="px-8 py-4">Pricing</th>
                                <th class="px-8 py-4 text-right">Expiration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($medicine->batches as $batch)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">
                                                B{{ $loop->iteration }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700">{{ $batch->batch_number }}</p>
                                                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Ref: #{{ $batch->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-black text-slate-700">{{ $batch->remaining_quantity }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $medicine->unit }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="space-y-1">
                                            <p class="text-sm font-bold text-blue-600">Rs. {{ number_format($batch->sale_price, 2) }} <span class="text-[10px] font-normal text-slate-400">Sale</span></p>
                                            <p class="text-xs text-slate-400">Rs. {{ number_format($batch->unit_price, 2) }} <span class="text-[9px] font-normal text-slate-300">Cost</span></p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @php
                                            $expiryDate = \Carbon\Carbon::parse($batch->expiry_date);
                                            $isExpired = $expiryDate->isPast();
                                            $isExpiringSoon = !$isExpired && $expiryDate->diffInDays(now()) <= 30;
                                        @endphp
                                        <div class="space-y-1">
                                            <p class="text-sm font-bold {{ $isExpired ? 'text-rose-600' : ($isExpiringSoon ? 'text-amber-600' : 'text-slate-700') }}">
                                                {{ $expiryDate->format('d M Y') }}
                                            </p>
                                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded {{ $isExpired ? 'bg-rose-100 text-rose-700' : ($isExpiringSoon ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                                {{ $isExpired ? 'Expired' : ($isExpiringSoon ? 'Expiring Soon' : 'Valid') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mx-auto mb-4">
                                            <i class="fas fa-box-open text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold">No stock batches found for this medicine.</p>
                                        <a href="{{ route('pharmacy.inventory.create', ['medicine_id' => $medicine->id]) }}" class="text-blue-600 text-sm font-black uppercase tracking-widest mt-2 block hover:underline">Add First Batch</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Medical Description & Notes</h3>
                <p class="text-slate-600 leading-relaxed italic text-sm">
                    {{ $medicine->description ?: 'No description provided for this medicine.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
