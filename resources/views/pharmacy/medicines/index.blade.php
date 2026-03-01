@extends('layouts.app')

@section('title', 'Medicine Catalog')
@section('page-title', 'Medicine Catalog')
@section('breadcrumb', 'Pharmacy / Medicines')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 leading-tight">Medicine Inventory</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-1">Manage global and branch-specific pharmaceutical stock</p>
        </div>
        <a href="{{ route('pharmacy.medicines.create') }}" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-bold hover:shadow-lg transition-all shadow-blue-200/50">
            <i class="fas fa-plus"></i> Add New Medicine
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
        <form action="{{ route('pharmacy.medicines.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, generic, or brand..." 
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder:text-slate-400">
            </div>
            
            <div class="flex flex-wrap gap-4">
                <select name="category" class="px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-600 appearance-none min-w-[150px]">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <select name="prescription" class="px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-600 appearance-none min-w-[180px]">
                    <option value="">All Statuses</option>
                    <option value="required" {{ request('prescription') == 'required' ? 'selected' : '' }}>Prescription Required</option>
                    <option value="not_required" {{ request('prescription') == 'not_required' ? 'selected' : '' }}>OTC / Not Required</option>
                </select>

                <button type="submit" class="px-8 py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all">
                    Filter Results
                </button>
                
                @if(request()->anyFilled(['search', 'category', 'prescription']))
                    <a href="{{ route('pharmacy.medicines.index') }}" class="px-4 py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Inventory Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($medicines as $medicine)
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                <!-- Status Badge -->
                <div class="absolute top-6 right-6">
                    @if($medicine->requires_prescription)
                        <span class="px-2 py-1 rounded-lg bg-rose-50 text-rose-600 text-[9px] font-black uppercase tracking-widest border border-rose-100" title="Prescription Required">
                            <i class="fas fa-file-medical mr-1"></i> Rx
                        </span>
                    @endif
                </div>

                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-blue-600 leading-tight group-hover:text-blue-800 transition-colors line-clamp-1">
                            {{ $medicine->name }}
                        </h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $medicine->generic_name }}</p>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Manufacturer</span>
                        <span class="text-slate-700 font-black">{{ Str::limit($medicine->manufacturer ?: 'N/A', 15) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Category</span>
                        <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-md text-[10px]">{{ $medicine->category->name ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-50 flex items-baseline justify-between">
                        <div>
                            <span class="text-2xl font-black text-slate-800">{{ $medicine->total_stock ?? 0 }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase ml-1">{{ $medicine->unit }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('pharmacy.medicines.show', $medicine) }}" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('pharmacy.medicines.edit', $medicine) }}" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Stock Bar indicator -->
                <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r {{ ($medicine->total_stock ?? 0) <= ($medicine->reorder_level ?? 0) ? 'from-rose-500 to-rose-600' : 'from-emerald-400 to-emerald-500' }}" style="width: {{ min(100, (($medicine->total_stock ?? 0) / (($medicine->reorder_level ?: 1) * 2)) * 100) }}%"></div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border-2 border-dashed border-slate-100">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-6">
                    <i class="fas fa-search text-4xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-400">No medicines found</h4>
                <p class="text-slate-300 mt-2 font-medium">Try adjusting your filters or search terms.</p>
                <a href="{{ route('pharmacy.medicines.create') }}" class="mt-6 inline-block text-blue-600 font-black uppercase tracking-widest text-sm hover:underline">Add New Medicine</a>
            </div>
        @endforelse
    </div>

    @if($medicines->hasPages())
        <div class="mt-10 px-6">
            {{ $medicines->links() }}
        </div>
    @endif
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
