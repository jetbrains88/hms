@extends('layouts.app')

@section('title', 'Lab Orders')

@section('page-title', 'Laboratory Orders')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Stats Cards -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Pending</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['pending'] }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-amber-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Processing</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['processing'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-flask text-blue-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Completed Today</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['completed_today'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Verified Today</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['verified_today'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shield-alt text-purple-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-soft p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-slate-800">Lab Orders</h3>
        <a href="{{ route('lab.orders.create') }}" 
           class="px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600">
            <i class="fas fa-plus mr-2"></i>New Order
        </a>
    </div>
    
    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <select class="px-3 py-2 border border-slate-200 rounded-xl text-sm" onchange="window.location.href = '?status=' + this.value">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        
        <select class="px-3 py-2 border border-slate-200 rounded-xl text-sm" onchange="window.location.href = '?priority=' + this.value">
            <option value="">All Priority</option>
            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
        </select>
        
        <form method="GET" class="flex-1 max-w-md">
            <div class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by lab number, patient name or EMRN..." 
                       class="flex-1 px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Lab #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Patient</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tests</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-sm font-medium text-slate-700">{{ $order->lab_number }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-700">{{ $order->patient->name }}</div>
                        <div class="text-xs text-slate-500">{{ $order->patient->emrn }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @foreach($order->items as $item)
                            <span class="inline-block px-2 py-1 bg-slate-100 text-xs rounded-full mr-1 mb-1">
                                {{ $item->labTestType->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3">
                        @if($order->priority == 'urgent')
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">Urgent</span>
                        @else
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs">Normal</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($order->status == 'pending')
                            <span class="px-2 py-1 bg-amber-100 text-amber-600 rounded-full text-xs">Pending</span>
                        @elseif($order->status == 'processing')
                            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">Processing</span>
                        @elseif($order->status == 'completed')
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs">Completed</span>
                        @endif
                        @if($order->is_verified)
                            <span class="ml-1 px-2 py-1 bg-purple-100 text-purple-600 rounded-full text-xs">Verified</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('lab.orders.show', $order) }}" 
                           class="text-blue-500 hover:text-blue-700 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($order->status == 'completed' && $order->is_verified)
                        <a href="{{ route('lab.orders.print', $order) }}" 
                           target="_blank"
                           class="text-green-500 hover:text-green-700">
                            <i class="fas fa-print"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                        No lab orders found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection