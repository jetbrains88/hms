<div x-show="showPermissionsModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">User Permissions</h3>
            <button @click="closePermissionsModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <div class="p-6">
            <!-- User Info -->
            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg mb-6">
                <div class="h-16 w-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg"
                     :class="selectedUser ? getAvatarColor(selectedUser.name) : 'bg-gray-400'">
                    <span x-text="selectedUser ? getInitials(selectedUser.name) : ''"></span>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-gray-900" x-text="selectedUser?.name"></h4>
                    <p class="text-gray-600" x-text="selectedUser?.email"></p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="role in selectedUser?.roles || []" :key="role.id">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  :class="getRoleBadgeClass(role.name)">
                                <i class="mr-1" :class="getRoleIconClass(role.name)"></i>
                                <span x-text="role.display_name || role.name"></span>
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Permissions List -->
            <div x-show="loadingPermissions" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading permissions...</p>
            </div>

            <div x-show="!loadingPermissions" class="space-y-6">
                <!-- Grouped Permissions -->
                <template x-for="(permissions, group) in groupedPermissions" :key="group">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h5 class="font-bold text-gray-700 capitalize" x-text="group"></h5>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <template x-for="permission in permissions" :key="permission.id">
                                <div class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded">
                                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                    <span class="text-sm text-gray-700" x-text="permission.display_name || permission.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- No Permissions Message -->
                <div x-show="Object.keys(groupedPermissions).length === 0" class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">No specific permissions assigned.</p>
                    <p class="text-sm text-gray-400 mt-1">Permissions are inherited from assigned roles.</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                <button @click="closePermissionsModal"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-xl transition-all shadow-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>