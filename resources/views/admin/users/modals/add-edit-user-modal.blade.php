<div x-show="showUserModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900" x-text="editingUser ? 'Edit User' : 'Add New User'"></h3>
            <button @click="closeUserModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form @submit.prevent="saveUser" class="p-6">
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" x-model="userForm.name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="John Doe">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" x-model="userForm.email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="john@example.com">
                </div>

                <!-- Password Fields -->
                <template x-if="!editingUser">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" x-model="userForm.password" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                       placeholder="Minimum 8 characters">
                                <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                            <input :type="showPassword ? 'text' : 'password'"
                                   x-model="userForm.password_confirmation" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Confirm password">
                        </div>
                    </div>
                </template>

                <!-- Password Change for Edit Mode -->
                <template x-if="editingUser">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                            <div>
                                <p class="font-medium text-blue-900">Change Password?</p>
                                <p class="text-sm text-blue-700">Leave empty to keep current password</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="changePassword" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <template x-if="changePassword">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <div class="relative">
                                        <input :type="showPassword ? 'text' : 'password'" x-model="userForm.password"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                               placeholder="Minimum 8 characters">
                                        <button type="button" @click="showPassword = !showPassword"
                                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input :type="showPassword ? 'text' : 'password'"
                                           x-model="userForm.password_confirmation"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Confirm new password">
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Role Selection (Multiple with Checkboxes) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Roles *</label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($roles as $role)
                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                    <input type="checkbox"
                                           x-model="userForm.role_ids"
                                           :value="{{ $role->id }}"
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2"
                                              :class="getRoleBadgeClass('{{ $role->name }}')">
                                            <i class="mr-1" :class="getRoleIconClass('{{ $role->name }}')"></i>
                                            {{ $role->display_name }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Select one or more roles for the user</p>
                </div>

                <!-- Branch Selection (Multiple with Checkboxes) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Branches *</label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto bg-gray-50/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <template x-for="branch in availableBranches" :key="branch.id">
                                <label class="flex items-center space-x-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-colors border border-transparent hover:border-gray-200 shadow-sm hover:shadow">
                                    <input type="checkbox"
                                           x-model="userForm.branch_ids"
                                           :value="branch.id"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-gray-900" x-text="branch.name"></span>
                                        <span class="text-[10px] text-gray-500" x-text="branch.type"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1 italic">Assign one or more branches to this user</p>
                </div>

                <!-- Primary Branch Selection (Visible only if branches are selected) -->
                <template x-if="userForm.branch_ids.length > 0">
                    <div class="p-4 bg-indigo-50/50 border border-indigo-100 rounded-xl">
                        <label class="block text-sm font-bold text-indigo-900 mb-2 flex items-center">
                            <i class="fas fa-star text-amber-400 mr-2"></i>
                            Primary Branch *
                        </label>
                        <select x-model="userForm.primary_branch_id" required
                                class="w-full px-4 py-3 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white shadow-sm">
                            <option value="">Select Primary Branch</option>
                            <template x-for="branchId in userForm.branch_ids" :key="branchId">
                                <option :value="branchId" x-text="availableBranches.find(b => b.id == branchId)?.name"></option>
                            </template>
                        </select>
                        <p class="text-[10px] text-indigo-600 mt-2">The primary branch defines the default view for the user.</p>
                    </div>
                </template>

                <!-- Status -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Account Status</p>
                        <p class="text-sm text-gray-500">Enable or disable user access</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="userForm.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" @click="closeUserModal"
                          class="px-6 py-2.5 text-white font-bold rounded-xl transition-all duration-200 
                            shadow-md hover:shadow-lg flex items-center justify-center gap-2
                            bg-gradient-to-r from-slate-600 via-gray-600 to-stone-600 
                            hover:from-slate-700 hover:via-gray-700 hover:to-red-700 
                            focus:ring-2 focus:ring-red-400 focus:outline-none
                            disabled:opacity-50 disabled:cursor-not-allowed border-0">
                    <i class="fas fa-times-circle mr-2 "></i> 
                    Cancel
                </button>
                <button type="submit" :disabled="saving"
                        :class="saving ? 'opacity-70 cursor-not-allowed' : ''"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-xl transition-all shadow-lg">
                    <span x-show="!saving" x-text="editingUser ? 'Update User' : 'Create User'"></span>
                    <span x-show="saving">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>