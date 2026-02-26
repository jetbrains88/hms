@extends('layouts.app')

@section('title', 'Branch Management')

@section('page-title', 'Hospital Branches')

@section('content')
<div class="space-y-6" x-data="branchManagement()">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Hospital Branches</h2>
            <p class="text-sm text-slate-500 font-medium">Manage medical centers and administrative offices across the network</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.branches.create') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 font-bold text-sm">
                <i class="fas fa-plus-circle mr-2"></i>Register New Branch
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-hospital"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Branches</p>
                <h4 class="text-2xl font-bold text-slate-800">{{ $branches->total() }}</h4>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Centers</p>
                <h4 class="text-2xl font-bold text-slate-800">{{ $branches->where('is_active', true)->count() }}</h4>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Global Staff</p>
                <h4 class="text-2xl font-bold text-slate-800">{{ \App\Models\User::count() }}</h4>
            </div>
        </div>
    </div>

    <!-- Branches List -->
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Branch Profile</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Location Info</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Staff Count</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($branches as $branch)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-11 w-11 rounded-2xl flex items-center justify-center text-xl font-bold shadow-sm transition-transform group-hover:scale-105
                                    {{ $branch->type == 'CMO' ? 'bg-gradient-to-br from-indigo-500 to-indigo-700 text-white' : 'bg-gradient-to-br from-violet-500 to-purple-600 text-white' }}">
                                    <i class="fas fa-hospital"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 text-base">{{ $branch->name }}</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase tracking-tighter {{ $branch->type == 'CMO' ? 'text-indigo-500' : 'text-purple-500' }}">
                                            {{ $branch->type }}
                                        </span>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ $branch->office->name ?? 'Level 1' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-slate-600">
                                <i class="fas fa-map-marker-alt text-slate-300"></i>
                                <span class="text-sm font-medium">{{ $branch->location ?? 'Headquarters' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center -space-x-2">
                                @forelse($branch->users->take(4) as $user)
                                <div class="h-8 w-8 rounded-xl ring-2 ring-white bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 border border-slate-200" title="{{ $user->name }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                @empty
                                <span class="text-[10px] font-bold text-slate-300 uppercase italic">No Staff</span>
                                @endforelse
                                @if($branch->users->count() > 4)
                                <div class="h-8 w-8 rounded-xl ring-2 ring-white bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-indigo-100">
                                    +{{ $branch->users->count() - 4 }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="toggleBranchStatus('{{ $branch->id }}', '{{ $branch->name }}', {{ $branch->is_active ? 'true' : 'false' }})"
                                class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-sm
                                {{ $branch->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                    {{ $branch->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.branches.show', $branch) }}" 
                                   class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Detailed Analytics">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="{{ route('admin.branches.edit', $branch) }}" 
                                   class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Modify Configuration">
                                    <i class="fas fa-cog"></i>
                                </a>
                                <button type="button" @click="confirmDelete('{{ $branch->id }}', '{{ $branch->name }}')"
                                    class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Archive Branch">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-hospital text-4xl text-slate-100 mb-4"></i>
                                <h3 class="text-lg font-bold text-slate-800">No Branches Registered</h3>
                                <p class="text-slate-500 text-sm">Expand your hospital network by adding your first branch.</p>
                                <a href="{{ route('admin.branches.create') }}" class="mt-6 px-4 py-2 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 font-bold">
                                    Get Started
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($branches->hasPages())
        <div class="px-6 py-6 border-t border-slate-100 bg-slate-50/30">
            {{ $branches->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function branchManagement() {
    return {
        async toggleBranchStatus(id, name, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            if (!confirm(`Are you sure you want to ${action} ${name}?`)) return;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/admin/branches/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                if (result.success) {
                    window.showNotification(result.message, 'success');
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    window.showNotification(result.message || 'Operation failed', 'error');
                }
            } catch (error) {
                window.showNotification('Network error occurred', 'error');
            }
        },

        confirmDelete(id, name) {
            if (confirm(`CRITICAL: Are you sure you want to archive ${name}? This action might affect associated data.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/branches/${id}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
</script>
@endsection
