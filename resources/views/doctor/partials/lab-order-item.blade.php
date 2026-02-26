<div class="bg-gray-50 rounded-xl border border-gray-200 p-4 relative group">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-microscope text-indigo-600"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-gray-900">Lab Order #{{ $order->lab_number }}</div>
                <div class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2 py-1 {{ $order->status_badge_class }} text-[10px] font-bold uppercase rounded">
                {{ $order->status }}
            </span>
            <span class="px-2 py-1 {{ $order->priority_badge_class }} text-[10px] font-bold uppercase rounded">
                {{ $order->priority }}
            </span>
        </div>
    </div>

    <div class="space-y-2">
        @foreach($order->items as $item)
            <div class="flex items-center justify-between p-2 bg-white rounded border border-gray-100 text-sm">
                <span class="font-medium text-gray-700">{{ $item->labTestType->name }}</span>
                @if($item->status === 'completed')
                    <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-gray-400 text-xs italic">{{ $item->status }}</span>
                @endif
            </div>
        @endforeach
    </div>

    @if($order->comments)
        <div class="mt-3 p-2 bg-white rounded border-l-4 border-indigo-500 text-xs text-gray-600 italic">
            {{ $order->comments }}
        </div>
    @endif
</div>
