@extends('layouts.app')

@section('title', 'Create User')

@section('page-title', 'Create New User')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" x-data="userForm()">
                @csrf

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-md font-semibold text-slate-800 mb-4">Basic Information</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                                <input type="password" name="password" required
                                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" checked
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-slate-700">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Role Assignment -->
                    <div class="pt-4 border-t">
                        <h3 class="text-md font-semibold text-slate-800 mb-4">Roles</h3>

                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($roles as $role)
                                <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3">
                                        <span
                                            class="block text-sm font-medium text-slate-700">{{ $role->display_name }}</span>
                                        <span class="text-xs text-slate-500">{{ $role->name }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Branch Assignment -->
                    <div class="pt-4 border-t">
                        <h3 class="text-md font-semibold text-slate-800 mb-4">Branch Access</h3>

                        <div class="space-y-4">
                            @foreach ($branches as $branch)
                                <div class="flex items-center justify-between p-3 border border-slate-200 rounded-xl">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="branches[]" value="{{ $branch->id }}"
                                            x-model="selectedBranches" @change="updatePrimaryOptions"
                                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-3">
                                            <span
                                                class="block text-sm font-medium text-slate-700">{{ $branch->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $branch->type }} ·
                                                {{ $branch->location ?? 'No location' }}</span>
                                        </span>
                                    </label>

                                    <div x-show="selectedBranches.includes('{{ $branch->id }}')">
                                        <label class="flex items-center text-sm">
                                            <input type="radio" name="primary_branch" value="{{ $branch->id }}"
                                                class="mr-2 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                            Primary
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('branches')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600">
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function userForm() {
            return {
                selectedBranches: [],
                updatePrimaryOptions() {
                    // This will trigger re-render of radio buttons
                    this.selectedBranches = [...this.selectedBranches];
                }
            }
        }
    </script>
@endsection
