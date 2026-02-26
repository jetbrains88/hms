@extends('layouts.app')

@section('title', 'Settings')

@section('page-title', 'User Settings')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <form method="POST" action="{{ route('profile.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Theme Preference -->
                    <div>
                        <h3 class="text-md font-semibold text-slate-800 mb-4">Appearance</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative">
                                <input type="radio" name="theme" value="light" class="sr-only"
                                    {{ ($preferences['theme'] ?? 'light') == 'light' ? 'checked' : '' }}>
                                <div
                                    class="border-2 {{ ($preferences['theme'] ?? 'light') == 'light' ? 'border-blue-500 bg-blue-50' : 'border-slate-200' }} rounded-xl p-4 cursor-pointer hover:border-blue-300">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fas fa-sun text-2xl text-amber-500"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">Light Mode</p>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="theme" value="dark" class="sr-only"
                                    {{ ($preferences['theme'] ?? 'light') == 'dark' ? 'checked' : '' }}>
                                <div
                                    class="border-2 {{ ($preferences['theme'] ?? 'light') == 'dark' ? 'border-blue-500 bg-blue-50' : 'border-slate-200' }} rounded-xl p-4 cursor-pointer hover:border-blue-300">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fas fa-moon text-2xl text-slate-700"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">Dark Mode</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="pt-4 border-t">
                        <h3 class="text-md font-semibold text-slate-800 mb-4">Notifications</h3>

                        <div class="space-y-4">
                            <label class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div>
                                    <p class="font-medium text-slate-700">Email Notifications</p>
                                    <p class="text-xs text-slate-500">Receive notifications via email</p>
                                </div>
                                <div class="relative inline-block w-12 h-6 rounded-full cursor-pointer">
                                    <input type="checkbox" name="notifications_email" class="sr-only" value="1"
                                        {{ $preferences['notifications_email'] ?? true ? 'checked' : '' }}>
                                    <div
                                        class="toggle-bg w-12 h-6 rounded-full {{ $preferences['notifications_email'] ?? true ? 'bg-blue-500' : 'bg-slate-300' }}">
                                    </div>
                                    <div
                                        class="toggle-dot absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform {{ $preferences['notifications_email'] ?? true ? 'transform translate-x-6' : '' }}">
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div>
                                    <p class="font-medium text-slate-700">Browser Notifications</p>
                                    <p class="text-xs text-slate-500">Receive real-time browser notifications</p>
                                </div>
                                <div class="relative inline-block w-12 h-6 rounded-full cursor-pointer">
                                    <input type="checkbox" name="notifications_browser" class="sr-only" value="1"
                                        {{ $preferences['notifications_browser'] ?? true ? 'checked' : '' }}>
                                    <div
                                        class="toggle-bg w-12 h-6 rounded-full {{ $preferences['notifications_browser'] ?? true ? 'bg-blue-500' : 'bg-slate-300' }}">
                                    </div>
                                    <div
                                        class="toggle-dot absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform {{ $preferences['notifications_browser'] ?? true ? 'transform translate-x-6' : '' }}">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Language Preference -->
                    <div class="pt-4 border-t">
                        <h3 class="text-md font-semibold text-slate-800 mb-4">Language</h3>
                        <select name="language"
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="en" {{ ($preferences['language'] ?? 'en') == 'en' ? 'selected' : '' }}>
                                English</option>
                            <option value="ur" {{ ($preferences['language'] ?? 'en') == 'ur' ? 'selected' : '' }}>Urdu
                            </option>
                        </select>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('profile.show') }}"
                            class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .toggle-bg {
            transition: background-color 0.3s;
        }

        .toggle-dot {
            transition: transform 0.3s;
        }
    </style>
@endsection
