<div x-show="showDeleteModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-8 text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-rose-50 text-rose-600 animate-bounce">
                <i class="fas fa-trash-alt text-3xl"></i>
            </div>
            <h3 class="mt-6 text-xl font-bold text-slate-900">Archive Branch?</h3>
            <p class="mt-2 text-sm text-slate-500">
                Are you sure you want to archive <span class="font-bold text-slate-700" x-text="branchToDelete?.name"></span>? This action will disable the branch and might affect associated data.
            </p>

            <div class="mt-8 flex flex-col gap-3">
                <button @click="deleteBranch" :disabled="deleting"
                        class="w-full px-6 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-100 transition-all flex items-center justify-center gap-2">
                    <span x-show="!deleting">Yes, Archive Branch</span>
                    <span x-show="deleting">
                        <i class="fas fa-spinner fa-spin"></i> Archiving...
                    </span>
                </button>
                <button @click="showDeleteModal = false" :disabled="deleting"
                        class="w-full px-6 py-3 bg-slate-50 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-all">
                    No, Keep Branch
                </button>
            </div>
        </div>
    </div>
</div>
