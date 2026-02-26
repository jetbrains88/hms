<x-layout>
    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-blue-600 p-6 text-white">
                <h1 class="text-2xl font-bold">My Profile</h1>
                <p class="text-blue-100">Update your personal information and password</p>
            </div>

            <div class="p-6">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                   class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none"
                                   required>
                            @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none"
                                   required>
                            @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Role (Display only) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Role</label>
                        <div class="w-full border rounded-lg p-3 bg-gray-50">
                            <span class="px-3 py-1 rounded text-sm font-bold {{ auth()->user()->role_badge }}">
                                {{ auth()->user()->role_display }}
                            </span>
                            <p class="text-gray-500 text-sm mt-1">Your role is assigned by the system administrator</p>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Change Password</h3>
                        <p class="text-gray-600 text-sm mb-4">Leave blank to keep current password</p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password"
                                       class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                                @error('current_password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="new_password"
                                           class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                                    @error('new_password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Confirm New
                                        Password</label>
                                    <input type="password" name="new_password_confirmation"
                                           class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
