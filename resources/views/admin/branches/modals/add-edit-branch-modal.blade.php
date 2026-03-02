<div x-show="showBranchModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900" x-text="editingBranch ? 'Edit Branch' : 'Register New Branch'"></h3>
            <button @click="closeBranchModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form @submit.prevent="saveBranch" class="p-6">
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name *</label>
                    <input type="text" x-model="branchForm.name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                           placeholder="e.g. Islamabad Central Hospital">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Branch Type *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition-all"
                               :class="branchForm.type === 'CMO' ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200'">
                            <input type="radio" name="type" value="CMO" x-model="branchForm.type" class="sr-only">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-building text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">CMO</p>
                                    <p class="text-[10px] text-slate-500">Main Office</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition-all"
                               :class="branchForm.type === 'RMO' ? 'border-purple-500 bg-purple-50/50' : 'border-gray-200'">
                            <input type="radio" name="type" value="RMO" x-model="branchForm.type" class="sr-only">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                    <i class="fas fa-hospital text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">RMO</p>
                                    <p class="text-[10px] text-slate-500">Regional Office</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Office Selection (NHMP Hierarchy) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Office (NHMP Hierarchy)</label>
                    <div class="relative">
                        <select x-model="branchForm.office_id"
                                class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white appearance-none transition-all">
                            <option value="">Select Parent Office</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->type }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400 font-bold">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location/Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                        <input type="text" x-model="branchForm.location"
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="e.g. Sector H-8, Islamabad">
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-sm font-bold text-slate-800">Operating Status</p>
                        <p class="text-xs text-slate-500">Enable or disable this branch</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="branchForm.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" @click="closeBranchModal"
                        class="px-6 py-2.5 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-all">
                    Cancel
                </button>
                <button type="submit" :disabled="saving"
                        class="px-8 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-100 transition-all flex items-center gap-2">
                    <span x-show="!saving" x-text="editingBranch ? 'Update Branch' : 'Register Branch'"></span>
                    <span x-show="saving">
                        <i class="fas fa-spinner fa-spin"></i> Processing...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
