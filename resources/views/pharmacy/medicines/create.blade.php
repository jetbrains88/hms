@extends('layouts.app')

@section('title', 'Add New Medicine')
@section('page-title', 'Medicine Management')
@section('breadcrumb', 'Pharmacy / Medicines / Add')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('pharmacy.medicines.index') }}" class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-800 leading-tight">Add New Medicine</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-1">Initialize a new item in the pharmaceutical database</p>
        </div>
    </div>

    <form action="{{ route('pharmacy.medicines.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Basic Identification
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Trade Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Panadol" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder:font-medium">
                    @error('name') <p class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Generic Name *</label>
                    <input type="text" name="generic_name" value="{{ old('generic_name') }}" required placeholder="e.g. Paracetamol" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder:font-medium">
                    @error('generic_name') <p class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Medicine Category *</label>
                    <select name="category_id" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Manufacturer</label>
                    <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" placeholder="e.g. GSK" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder:font-medium">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Specifications & Strength
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Form *</label>
                    <select name="form_id" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                        <option value="">Select Form</option>
                        @foreach($forms as $form)
                            <option value="{{ $form->id }}" {{ old('form_id') == $form->id ? 'selected' : '' }}>{{ $form->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Strength Value</label>
                    <input type="text" name="strength_value" value="{{ old('strength_value') }}" placeholder="e.g. 500" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder:font-medium">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Strength Unit</label>
                    <input type="text" name="strength_unit" value="{{ old('strength_unit') }}" placeholder="e.g. mg" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder:font-medium">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Base Unit *</label>
                    <input type="text" name="unit" value="{{ old('unit', 'Tablet') }}" required placeholder="e.g. Tablet, Bottle" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder:font-medium">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Reorder Level *</label>
                    <input type="number" name="reorder_level" value="{{ old('reorder_level', 100) }}" required 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="flex items-center gap-4 pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="requires_prescription" value="1" {{ old('requires_prescription') ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Prescription Required</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Description / Notes</label>
                <textarea name="description" rows="3" placeholder="Additional medical information..." 
                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder:text-slate-400">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pb-10">
            <a href="{{ route('pharmacy.medicines.index') }}" class="px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all">Cancel</a>
            <button type="submit" class="px-12 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-black shadow-lg shadow-blue-200/50 hover:shadow-xl transition-all">
                Create Medicine Product
            </button>
        </div>
    </form>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
