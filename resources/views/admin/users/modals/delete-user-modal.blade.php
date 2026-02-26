<div x-show="showDeleteModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Delete User</h3>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete
                <span class="font-bold text-red-700" x-text="userToDelete ? userToDelete.name : ''"></span>?
                This action cannot be undone.
            </p>
            <div class="flex justify-center space-x-3">
                <button @click="closeDeleteModal"
                          class="px-6 py-2.5 text-white font-bold rounded-xl transition-all duration-200 
                            shadow-md hover:shadow-lg flex items-center justify-center gap-2
                            bg-gradient-to-r from-slate-600 via-gray-600 to-stone-600 
                            hover:from-slate-700 hover:via-gray-700 hover:to-red-700 
                            focus:ring-2 focus:ring-red-400 focus:outline-none
                            disabled:opacity-50 disabled:cursor-not-allowed border-0">
                    <i class="fas fa-times-circle mr-2 "></i> 
                    Cancel
                </button>
                <button @click="confirmDeleteAction" :disabled="deleting"
                        :class="deleting ? 'opacity-70 cursor-not-allowed' : ''"
                        class="px-6 py-2.5 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">
                    <span x-show="!deleting">Delete User</span>
                    <span x-show="deleting">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Deleting...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>