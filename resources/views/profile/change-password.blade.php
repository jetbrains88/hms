@extends('layouts.app')

@section('title', 'Change Password')

@section('page-title', 'Change Password')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <form method="POST" action="{{ route('profile.change-password') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                        <input type="password" name="new_password" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 @error('new_password') border-red-500 @enderror">
                        @error('new_password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-amber-700 mb-2">Password Requirements:</h4>
                        <ul class="text-xs text-amber-600 space-y-1 list-disc list-inside">
                            <li>Minimum 8 characters long</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one lowercase letter</li>
                            <li>At least one number</li>
                            <li>At least one special character</li>
                        </ul>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('profile.show') }}"
                            class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600">
                            Change Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
