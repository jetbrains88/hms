.@extends('layouts.app')

@section('title', 'Notifications')

@section('page-title', 'All Notifications')

@section('content')
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Notifications</h3>
                <p class="text-sm text-slate-500">You have {{ $notifications->whereNull('read_at')->count() }} unread
                    notifications</p>
            </div>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100">
                        <i class="fas fa-check-double mr-2"></i>Mark All Read
                    </button>
                </form>
                <select class="px-3 py-2 border border-slate-200 rounded-xl text-sm"
                    onchange="window.location.href = '?type=' + this.value">
                    <option value="">All Types</option>
                    <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                    <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Error</option>
                </select>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
                <div
                    class="flex items-start gap-4 p-4 {{ is_null($notification->read_at) ? 'bg-blue-50' : 'bg-white' }} rounded-xl border {{ is_null($notification->read_at) ? 'border-blue-200' : 'border-slate-100' }} hover:shadow-md transition-shadow">
                    <!-- Icon -->
                    <div
                        class="w-10 h-10 rounded-full 
                @if ($notification->type == 'success') bg-green-100
                @elseif($notification->type == 'error') bg-red-100
                @elseif($notification->type == 'warning') bg-amber-100
                @else bg-blue-100 @endif
                flex items-center justify-center">
                        <i
                            class="fas 
                    @if ($notification->type == 'success') fa-check-circle text-green-600
                    @elseif($notification->type == 'error') fa-exclamation-circle text-red-600
                    @elseif($notification->type == 'warning') fa-exclamation-triangle text-amber-600
                    @else fa-info-circle text-blue-600 @endif
                "></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-semibold text-slate-800">{{ $notification->title }}</h4>
                            <span class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-600 mb-2">{{ $notification->body }}</p>

                        <!-- Related Info -->
                        @if ($notification->related)
                            <div class="text-xs text-slate-500 mb-2">
                                <i class="fas fa-link mr-1"></i>
                                Related to: {{ class_basename($notification->related_type) }}
                                #{{ $notification->related_id }}
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-3 mt-2">
                            @if ($notification->action_url)
                                <a href="{{ $notification->action_url }}"
                                    class="text-xs text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-external-link-alt mr-1"></i>{{ $notification->action_text ?? 'View' }}
                                </a>
                            @endif

                            @if (is_null($notification->read_at))
                                <form method="POST" action="{{ route('notifications.mark-as-read', $notification) }}"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-green-500 hover:text-green-700">
                                        <i class="fas fa-check mr-1"></i>Mark as Read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Unread Indicator -->
                    @if (is_null($notification->read_at))
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No Notifications</h3>
                    <p class="text-slate-500">You're all caught up!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
@endsection
