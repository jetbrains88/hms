<div x-show="showDetailsModal" x-cloak
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Sticky Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-search-plus"></i>
                    Audit Log Details
                </h3>
                <p class="text-indigo-100 text-sm font-medium mt-1">Detailed breakdown of system activity</p>
            </div>
            <button @click="showDetailsModal = false" class="text-indigo-100 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Content Area -->
        <div class="p-8 overflow-y-auto">
            <template x-if="selectedLog">
                <div class="space-y-8">
                    <!-- Basic Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Timestamp</p>
                            <p class="text-sm font-bold text-slate-700" x-text="formatFullDate(selectedLog.created_at)"></p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Action</p>
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm border"
                                  :class="getActionClass(selectedLog.action)"
                                  x-text="selectedLog.action"></span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Performed By</p>
                            <p class="text-sm font-bold text-slate-700" x-text="selectedLog.user?.name || 'System Process'"></p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Branch</p>
                            <p class="text-sm font-bold text-slate-700" x-text="selectedLog.branch?.name || 'System Core'"></p>
                        </div>
                    </div>

                    <!-- Target Entity -->
                    <div class="bg-indigo-50/50 p-6 rounded-3xl border border-indigo-100 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-200">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-0.5">Target Entity</p>
                            <h4 class="text-lg font-bold text-slate-800">
                                <span x-text="selectedLog.entity_type.split('\\').pop()"></span>
                                <span class="text-slate-400 font-medium ml-2 text-sm" x-text="`#${selectedLog.entity_id}`"></span>
                            </h4>
                        </div>
                    </div>

                    <!-- Comparison Table -->
                    <div x-show="selectedLog.details && selectedLog.details.length > 0">
                        <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-exchange-alt text-indigo-500"></i>
                            Changes Detected
                        </h4>
                        <div class="border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-1/4">Field</th>
                                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-full">Detailed Change</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <template x-for="detail in selectedLog.details" :key="detail.id">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-slate-700" x-text="formatFieldName(detail.field)"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                    <!-- Old Value -->
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black text-rose-400 uppercase tracking-tighter">Previous Value</p>
                                                        <div class="p-3 bg-rose-50/50 border border-rose-100 rounded-xl min-h-[40px]">
                                                            <p class="text-xs font-mono text-rose-700 break-all" x-text="detail.old_value || 'None'"></p>
                                                        </div>
                                                    </div>
                                                    <!-- New Value -->
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black text-emerald-400 uppercase tracking-tighter">New Value</p>
                                                        <div class="p-3 bg-emerald-50/50 border border-emerald-100 rounded-xl min-h-[40px]">
                                                            <p class="text-xs font-mono text-emerald-700 break-all" x-text="detail.new_value || 'None'"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- No Details Fallback -->
                    <div x-show="!selectedLog.details || selectedLog.details.length === 0" 
                         class="py-12 border-2 border-dashed border-slate-100 rounded-3xl text-center">
                        <i class="fas fa-info-circle text-slate-200 text-4xl mb-4"></i>
                        <p class="text-slate-400 font-medium">No granular field changes recorded for this action.</p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 shrink-0 flex justify-end">
            <button @click="showDetailsModal = false" 
                    class="px-6 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm">
                Close Details
            </button>
        </div>
    </div>
</div>
