@forelse($notifications as $notification)
    <a href="#" class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 transition-colors">
        <div class="flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                    <i class="fas fa-flask text-xs"></i>
                </div>
            </div>
            <div class="ml-3 w-0 flex-1">
                <p class="text-sm font-medium text-slate-800">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(is_null($notification->read_at))
                <div class="ml-auto pl-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                </div>
            @endif
        </div>
    </a>
@empty
    <div class="p-4 text-center text-slate-400 text-xs">
        No new notifications
    </div>
@endforelse