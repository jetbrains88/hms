@extends('layouts.app')

@section('title', 'Edit Profile')

@section('page-title', 'Edit Profile')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                            <div>
                                <p class="text-sm text-blue-700">
                                    Your email address is used for login and notifications.
                                    Make sure it's accurate.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('profile.show') }}"
                            class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">
                            Update Profile
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
