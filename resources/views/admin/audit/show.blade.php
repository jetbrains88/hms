@extends('layouts.app')

@section('title', 'Audit Log Details')

@section('page-title', 'Audit Log: #' . $auditLog->id)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">Admin</a>
    <span class="mx-2 text-gray-400">/</span>
    <a href="{{ route('admin.audit.index') }}" class="text-indigo-600 hover:text-indigo-900">Audit Logs</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-600">Details</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Main Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Log Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft p-6 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Action Information</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                        @if($auditLog->action == 'created') bg-green-100 text-green-700
                        @elseif($auditLog->action == 'updated') bg-blue-100 text-blue-700
                        @elseif($auditLog->action == 'deleted') bg-red-100 text-red-700
                        @else bg-slate-100 text-slate-700 @endif">
                        {{ $auditLog->action }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Entity Type</p>
                        <p class="text-sm font-medium text-slate-700">{{ class_basename($auditLog->entity_type) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Entity ID</p>
                        <p class="text-sm font-medium text-slate-700">{{ $auditLog->entity_id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Date & Time</p>
                        <p class="text-sm font-medium text-slate-700">{{ $auditLog->created_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">IP Address</p>
                        <p class="text-sm font-medium text-slate-700">{{ $auditLog->ip_address }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description</p>
                    <p class="text-sm text-slate-600 italic">
                        {{ $auditLog->description ?: 'No additional description provided.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Performer Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-soft p-6 h-full">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Performed By</h3>
                
                @if($auditLog->user)
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            {{ strtoupper(substr($auditLog->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $auditLog->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $auditLog->user->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">User ID:</span>
                            <span class="font-medium text-slate-700">{{ $auditLog->user->id }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Branch:</span>
                            <span class="font-medium text-slate-700">{{ $auditLog->branch->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                            <i class="fas fa-robot"></i>
                        </div>
                        <p class="font-medium text-slate-600">System Action</p>
                        <p class="text-xs text-slate-400">Performed by automated process</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Data Changes -->
    @if($auditLog->details->count() > 0)
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Field Changes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30">
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">Field</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-3/8">Old Value</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-3/8">New Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($auditLog->details as $detail)
                    <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $detail->field_name }}</td>
                        <td class="px-6 py-4">
                            <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded break-all">{{ $detail->old_value ?: '(empty)' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded break-all">{{ $detail->new_value ?: '(empty)' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
