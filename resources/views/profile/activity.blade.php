@extends('layouts.app')

@section('title', 'Activity Log')

@section('page-title', 'My Activity Log')

@section('content')
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-slate-800">Recent Activity</h3>
            <div class="text-sm text-slate-500">
                Total: {{ $logs->total() }} activities
            </div>
        </div>

        <!-- Timeline -->
        <div class="space-y-4">
            @forelse($logs as $log)
                <div class="relative pl-8 pb-4 border-l-2 border-slate-200 last:border-0 last:pb-0">
                    <!-- Timeline Dot -->
                    <div
                        class="absolute -left-2 top-0 w-4 h-4 rounded-full 
                @if ($log->action == 'created') bg-green-500
                @elseif($log->action == 'updated') bg-blue-500
                @elseif($log->action == 'deleted') bg-red-500
                @else bg-slate-500 @endif
                border-2 border-white shadow-sm">
                    </div>

                    <!-- Activity Content -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                            @if ($log->action == 'created') bg-green-100 text-green-600
                            @elseif($log->action == 'updated') bg-blue-100 text-blue-600
                            @elseif($log->action == 'deleted') bg-red-100 text-red-600
                            @else bg-slate-100 text-slate-600 @endif">
                                    {{ ucfirst($log->action) }}
                                </span>
                                <span class="ml-2 text-sm font-medium text-slate-700">
                                    {{ class_basename($log->entity_type) }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-400">
                                {{ $log->created_at->format('d M Y H:i:s') }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-600 mb-2">
                            @if ($log->branch)
                                Branch: {{ $log->branch->name }}
                            @endif
                            @if ($log->ip_address)
                                · IP: {{ $log->ip_address }}
                            @endif
                        </p>

                        @if ($log->details->count() > 0)
                            <div class="mt-3 bg-white rounded-lg p-3">
                                <p class="text-xs font-medium text-slate-500 mb-2">Changed Fields:</p>
                                <div class="space-y-2">
                                    @foreach ($log->details as $detail)
                                        <div class="text-xs">
                                            <span class="font-medium text-slate-700">{{ $detail->field_name }}:</span>
                                            <span
                                                class="text-slate-500 line-through mr-2">{{ $detail->old_value ?? 'empty' }}</span>
                                            <i class="fas fa-arrow-right text-slate-400 mx-1"></i>
                                            <span class="text-green-600">{{ $detail->new_value ?? 'empty' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500">No activity recorded yet</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
