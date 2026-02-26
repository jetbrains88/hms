@extends('layouts.app')

@section('title', 'User Permissions')

@section('page-title', 'Active Permissions: ' . $user->name)

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">Users</a>
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">{{ $user->name }}</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-600">Permissions</span>
@endsection

@section('content')
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Effective Permissions</h3>
                <p class="text-sm text-slate-500">Calculated from all assigned roles</p>
            </div>
            <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-bold border border-indigo-100 italic">
                Total: {{ $permissions->count() }} Perms
            </div>
        </div>

        @php
            $grouped = $permissions->groupBy('group');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($grouped as $group => $items)
                <div class="border border-slate-100 rounded-2xl p-5 bg-slate-50/50">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                        {{ str_replace('_', ' ', $group) }}
                    </h4>
                    <div class="space-y-2">
                        @foreach($items as $permission)
                            <div class="flex items-center gap-2 bg-white p-2 rounded-lg border border-slate-100 shadow-sm">
                                <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                <span class="text-xs font-medium text-slate-600">{{ $permission->display_name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if($permissions->isEmpty())
            <div class="text-center py-20">
                <i class="fas fa-shield-virus text-6xl text-slate-200 mb-4"></i>
                <p class="text-slate-500">No permissions found for this user. Check role assignments.</p>
            </div>
        @endif
    </div>
@endsection
