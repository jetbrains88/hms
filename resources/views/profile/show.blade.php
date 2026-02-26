@extends('layouts.app')

@section('title', 'My Profile')

@section('page-title', 'My Profile')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Summary Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 p-1 mb-4">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                            <span class="text-3xl font-bold text-blue-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 mb-2">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-2 justify-center mb-4">
                        @foreach ($user->roles as $role)
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">
                                {{ $role->display_name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="w-full border-t border-slate-100 pt-4 mt-2">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-500">Member since:</span>
                            <span class="font-medium text-slate-700">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-500">Last login:</span>
                            <span
                                class="font-medium text-slate-700">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Status:</span>
                            @if ($user->is_active)
                                <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-xs">Active</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-soft p-6 mt-6">
                <h3 class="text-md font-semibold text-slate-800 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center p-3 bg-slate-50 rounded-xl hover:bg-blue-50 transition-colors">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-edit text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-700">Edit Profile</p>
                            <p class="text-xs text-slate-500">Update your personal information</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.change-password') }}"
                        class="flex items-center p-3 bg-slate-50 rounded-xl hover:bg-amber-50 transition-colors">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-key text-amber-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-700">Change Password</p>
                            <p class="text-xs text-slate-500">Update your password</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.settings') }}"
                        class="flex items-center p-3 bg-slate-50 rounded-xl hover:bg-purple-50 transition-colors">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-purple-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-700">Settings</p>
                            <p class="text-xs text-slate-500">Notification preferences</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.activity') }}"
                        class="flex items-center p-3 bg-slate-50 rounded-xl hover:bg-green-50 transition-colors">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-history text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-700">Activity Log</p>
                            <p class="text-xs text-slate-500">View your recent activity</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Assigned Branches -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Assigned Branches</h3>

                <div class="space-y-4">
                    @foreach ($user->branches as $branch)
                        <div
                            class="border border-slate-200 rounded-xl p-4 {{ $branch->pivot->is_primary ? 'bg-blue-50 border-blue-200' : '' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg {{ $branch->type == 'CMO' ? 'bg-purple-100' : 'bg-blue-100' }} flex items-center justify-center">
                                        <i
                                            class="fas {{ $branch->type == 'CMO' ? 'fa-building' : 'fa-code-branch' }} {{ $branch->type == 'CMO' ? 'text-purple-600' : 'text-blue-600' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-800">{{ $branch->name }}</h4>
                                        <p class="text-sm text-slate-500">{{ $branch->location ?? 'No location' }}</p>
                                        <div class="flex gap-2 mt-2">
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full text-xs">
                                                {{ $branch->type }}
                                            </span>
                                            @if ($branch->pivot->is_primary)
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-600 rounded-full text-xs">
                                                    Primary Branch
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if (!$branch->pivot->is_primary && $user->branches->count() > 1)
                                    <form method="POST" action="{{ route('branch.switch', $branch) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-500 hover:text-blue-700">
                                            Switch to this branch
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-soft p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Recent Activity</h3>
                    <a href="{{ route('profile.activity') }}" class="text-sm text-blue-500 hover:text-blue-700">View
                        all</a>
                </div>

                @if ($user->auditLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach ($user->auditLogs->take(5) as $log)
                            <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                                <div
                                    class="w-8 h-8 rounded-full 
                            @if ($log->action == 'created') bg-green-100
                            @elseif($log->action == 'updated') bg-blue-100
                            @elseif($log->action == 'deleted') bg-red-100
                            @else bg-slate-100 @endif
                            flex items-center justify-center">
                                    <i
                                        class="fas 
                                @if ($log->action == 'created') fa-plus text-green-600
                                @elseif($log->action == 'updated') fa-edit text-blue-600
                                @elseif($log->action == 'deleted') fa-trash text-red-600
                                @else fa-circle text-slate-600 @endif
                            "></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-slate-700">
                                        <span class="font-medium">{{ $log->action }}</span>
                                        {{ class_basename($log->entity_type) }}
                                        @if ($log->details->count() > 0)
                                            ({{ $log->details->count() }} fields changed)
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-slate-500 py-4">No recent activity</p>
                @endif
            </div>
        </div>
    </div>
@endsection
