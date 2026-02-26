<div x-show="showResetPasswordModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Reset Password</h3>
            <button @click="closeResetPasswordModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form @submit.prevent="confirmResetPassword" class="p-6">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-key text-orange-600 text-2xl"></i>
                </div>
                <p class="text-gray-600">
                    Reset password for <span class="font-bold text-gray-900" x-text="selectedUser?.name"></span>
                </p>
                <p class="text-sm text-gray-500 mt-2">A new password will be generated and sent to the user's email.</p>
            </div>

            <div class="space-y-4">
                <!-- Password Generation Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Method</label>
                    <select x-model="resetPasswordMethod" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="auto">Auto-generate password</option>
                        <option value="manual">Set custom password</option>
                    </select>
                </div>

                <!-- Custom Password Fields -->
                <template x-if="resetPasswordMethod === 'manual'">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <div class="relative">
                                <input :type="showResetPassword ? 'text' : 'password'" x-model="resetPassword" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                       placeholder="Minimum 8 characters">
                                <button type="button" @click="showResetPassword = !showResetPassword"
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i :class="showResetPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input :type="showResetPassword ? 'text' : 'password'"
                                   x-model="resetPasswordConfirmation" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Confirm new password">
                        </div>
                    </div>
                </template>

                <!-- Email Notification -->
                <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                    <input type="checkbox" x-model="sendEmailNotification" id="sendEmail" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="sendEmail" class="ml-3 text-sm text-gray-700">Send password to user via email</label>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" @click="closeResetPasswordModal"
                      class="px-6 py-2.5 text-white font-bold rounded-xl transition-all duration-200 
                            shadow-md hover:shadow-lg flex items-center justify-center gap-2
                            bg-gradient-to-r from-slate-600 via-gray-600 to-stone-600 
                            hover:from-slate-700 hover:via-gray-700 hover:to-red-700 
                            focus:ring-2 focus:ring-red-400 focus:outline-none
                            disabled:opacity-50 disabled:cursor-not-allowed border-0">
                    <i class="fas fa-times-circle mr-2 "></i> 
                    Cancel
                </button>
                <button type="submit" :disabled="resettingPassword"
                        :class="resettingPassword ? 'opacity-70 cursor-not-allowed' : ''"
                        class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-xl hover:shadow-xl transition-all shadow-lg">
                    <span x-show="!resettingPassword">Reset Password</span>
                    <span x-show="resettingPassword">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Resetting...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>