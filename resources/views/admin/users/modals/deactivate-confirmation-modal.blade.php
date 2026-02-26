<div x-show="showDeactivateModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="p-6 text-center">
            <!-- Icon with dynamic background based on action -->
            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4"
                 :class="deactivateAction === 'deactivate' ? 'bg-gradient-to-r from-maroon-400 to-maroon-500' : 'bg-gradient-to-r from-green-400 to-green-500'">
                <i class="fas text-white text-2xl" 
                   :class="deactivateAction === 'deactivate' ? 'fa-exclamation-circle' : 'fa-check-circle'"></i>
            </div>
            
            <!-- Title with dynamic color -->
            <h3 class="text-xl font-bold mb-2"
                :class="deactivateAction === 'deactivate' ? 'text-maroon-700' : 'text-green-700'">
                <span x-text="deactivateAction === 'deactivate' ? 'Deactivate User' : 'Activate User'"></span>
            </h3>
            
            <!-- User name highlight -->
            <p class="text-gray-600 mb-6">
                Are you sure you want to <span class="font-bold" x-text="deactivateAction"></span>
                <span class="font-bold px-2 py-0.5 rounded-lg" 
                     :class="deactivateAction === 'deactivate' ? 'bg-maroon-100 text-maroon-800' : 'bg-green-100 text-green-800'"
                     x-text="selectedUser?.name || ''"></span>?
            </p>
            
            <!-- Info box for deactivation -->
            <template x-if="deactivateAction === 'deactivate'">
                <div class="bg-gradient-to-r from-maroon-50 to-amber-50 border-l-4 border-maroon-500 p-4 rounded-lg mb-6 text-left shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-maroon-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-maroon-800">
                                Deactivating this user will:
                            </p>
                            <ul class="text-sm text-maroon-700 mt-2 space-y-1.5">
                                <li class="flex items-center">
                                    <i class="fas fa-times-circle text-xs mr-2 text-maroon-600"></i>
                                    Prevent them from logging in
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-times-circle text-xs mr-2 text-maroon-600"></i>
                                    Revoke all access to the system
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-xs mr-2 text-maroon-600"></i>
                                    Keep all their records intact
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Info box for activation -->
            <template x-if="deactivateAction === 'activate'">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 text-left shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                Activating this user will:
                            </p>
                            <ul class="text-sm text-green-700 mt-2 space-y-1.5">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-xs mr-2 text-green-600"></i>
                                    Restore their login access
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-xs mr-2 text-green-600"></i>
                                    Reinstate their previous permissions
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-xs mr-2 text-green-600"></i>
                                    Allow them to use the system again
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-3 mt-2">
                <!-- Cancel Button -->
          
                <button @click="closeDeactivateModal"
                        class="px-6 py-2.5 text-white font-bold rounded-xl transition-all duration-200 
                            shadow-md hover:shadow-lg flex items-center justify-center gap-2
                            bg-gradient-to-r from-slate-600 via-gray-600 to-stone-600 
                            hover:from-slate-700 hover:via-gray-700 hover:to-red-700 
                            focus:ring-2 focus:ring-red-400 focus:outline-none
                            disabled:opacity-50 disabled:cursor-not-allowed border-0">
                    <i class="fas fa-times-circle mr-2 "></i> 
                    Cancel
                </button>

                <!-- Confirm Button with dynamic gradients -->
                <button @click="confirmDeactivateAction" 
                        :disabled="processingDeactivate"
                        :class="[
                            'px-6 py-2.5 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl focus:ring-2 focus:outline-none',
                            processingDeactivate ? 'opacity-70 cursor-not-allowed' : '',
                            deactivateAction === 'deactivate' 
                                ? 'bg-gradient-to-r from-orange-600 via-maroon-500 to-amber-600 hover:from-maroon-700 hover:via-maroon-600 hover:to-amber-700 focus:ring-maroon-400' 
                                : 'bg-gradient-to-r from-green-600 via-green-500 to-emerald-600 hover:from-green-700 hover:via-green-600 hover:to-emerald-700 focus:ring-green-400'
                        ]">
                    <!-- Processing state -->
                    <span x-show="processingDeactivate" class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <span>Processing...</span>
                    </span>
                    
                    <!-- Normal state -->
                    <span x-show="!processingDeactivate" class="flex items-center">
                        <i class="fas mr-2" 
                           :class="deactivateAction === 'deactivate' ? 'fa-ban' : 'fa-check-circle'"></i>
                        <span x-text="deactivateAction === 'deactivate' ? 'Deactivate User' : 'Activate User'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>