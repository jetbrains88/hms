@extends('layouts.app')

@section('title', 'Add New Role')
@section('page-title', 'Add New Role')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.roles.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-200 text-slate-500 hover:text-indigo-600 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Add New Role</h1>
                    <p class="text-sm text-slate-500">Create a new system role and assign permissions</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6" x-data="roleCreate({{ json_encode($permissions) }})">
            @csrf

            <!-- Role Details Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-500"></i>
                        General Information
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Role Name (Slug)</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-mono text-sm"
                            placeholder="e.g. ward_manager" required>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">System unique identifier (no spaces)</p>
                        @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Display Name</label>
                        <input type="text" name="display_name" value="{{ old('display_name') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                            placeholder="e.g. Ward Manager" required>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">Human readable name</p>
                        @error('display_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Permissions Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-emerald-500"></i>
                        Role Permissions
                    </h3>
                    <div class="flex gap-4">
                        <button type="button" @click="selectAll()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider">Select All</button>
                        <button type="button" @click="deselectAll()" class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider">Deselect All</button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-8">
                        @foreach($permissions as $group => $groupPermissions)
                        <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100">
                            <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-folder-open text-xs"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">{{ str_replace('_', ' ', $group) }}</h4>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Group Select</span>
                                    <input type="checkbox" @change="toggleGroup('{{ $group }}', $event.target.checked)" :checked="isGroupSelected('{{ $group }}')" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                </label>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($groupPermissions as $permission)
                                <label class="relative flex items-center p-3 rounded-xl border border-slate-200 bg-white hover:border-indigo-200 hover:bg-indigo-50/30 transition-all cursor-pointer group">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                            x-model="selectedPermissions"
                                            class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 transition-all"
                                            {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition-colors">{{ $permission->display_name }}</span>
                                        <span class="block text-[10px] text-slate-400 font-mono">{{ $permission->name }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <span x-text="selectedPermissions.length"></span> permissions selected
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 font-bold text-slate-700 hover:bg-slate-200 rounded-xl transition-all text-sm">Cancel</a>
                        <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all text-sm flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Create Role
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function roleCreate(allPermissions) {
        return {
            selectedPermissions: @json(old('permissions', [])),
            allPermissions: allPermissions,
            
            selectAll() {
                const allIds = [];
                Object.values(this.allPermissions).forEach(group => {
                    group.forEach(p => allIds.push(p.id));
                });
                this.selectedPermissions = allIds;
            },
            
            deselectAll() {
                this.selectedPermissions = [];
            },
            
            toggleGroup(groupName, checked) {
                const groupIds = this.allPermissions[groupName].map(p => p.id);
                if (checked) {
                    groupIds.forEach(id => {
                        if (!this.selectedPermissions.includes(id)) {
                            this.selectedPermissions.push(id.toString()); // Alpine x-model with checkbox values often treats them as strings
                        }
                    });
                } else {
                    this.selectedPermissions = this.selectedPermissions.filter(id => !groupIds.includes(parseInt(id)) && !groupIds.includes(id.toString()));
                }
            },
            
            isGroupSelected(groupName) {
                const groupIds = this.allPermissions[groupName].map(p => p.id);
                return groupIds.every(id => this.selectedPermissions.includes(id) || this.selectedPermissions.includes(id.toString()));
            }
        }
    }
</script>
@endsection
