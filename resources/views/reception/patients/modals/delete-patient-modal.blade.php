{{-- reception/patients/modals/delete-patient-modal.blade.php --}}
<div x-show="showDeleteModal"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     x-data="{
        init() {
            this.$watch('showDeleteModal', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
     }"
     @keydown.escape.window="showDeleteModal = false">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
         @click="showDeleteModal = false"
         x-show="showDeleteModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 flex items-center justify-center p-4"
         x-show="showDeleteModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto overflow-hidden"
             @click.stop>
            
            <!-- Header with Icon -->
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 mb-4">
                    <i class="fas fa-trash-alt text-3xl text-rose-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Delete Patient</h3>
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete this patient? This action cannot be undone.
                </p>
            </div>

            <!-- Patient Info (if available) -->
            <div class="px-6 py-4 bg-gradient-to-r from-rose-50 to-pink-50 mx-6 rounded-xl mb-4"
                 x-show="userToDelete">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-lg"
                             :class="userToDelete ? getAvatarColor(userToDelete.name, userToDelete.gender) : 'bg-gradient-to-br from-gray-400 to-gray-600'">
                            <i class="fas" 
                               :class="userToDelete && userToDelete.gender === 'male' ? 'fa-mars' : 
                                      userToDelete && userToDelete.gender === 'female' ? 'fa-venus' : 
                                      'fa-user-injured'"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate" x-text="userToDelete?.name"></p>
                        <p class="text-xs text-gray-600 truncate" x-text="userToDelete?.emrn || userToDelete?.cnic"></p>
                        <p class="text-xs text-gray-500 mt-1">
                            <span x-text="userToDelete?.total_visits || 0"></span> total visits
                        </p>
                    </div>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="px-6 mb-6">
                <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-lg border border-amber-200">
                    <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-amber-800">Warning</p>
                        <p class="text-xs text-amber-700 mt-1">
                            Deleting this patient will permanently remove all associated records including:
                        </p>
                        <ul class="text-xs text-amber-700 mt-2 list-disc list-inside space-y-1">
                            <li>Personal information and demographics</li>
                            <li>Medical history and records</li>
                            <li>All visit history and appointments</li>
                            <li>Prescriptions and lab reports</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3">
                <button @click="showDeleteModal = false"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 font-medium text-sm">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                <button @click="confirmDeleteAction()"
                        :disabled="deleting"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white rounded-xl transition-all duration-200 font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                    <i class="fas fa-trash mr-2" x-show="!deleting"></i>
                    <i class="fas fa-circle-notch fa-spin mr-2" x-show="deleting"></i>
                    <span x-show="!deleting">Delete Patient</span>
                    <span x-show="deleting">Deleting...</span>
                </button>
            </div>

            <!-- Close Button -->
            <button @click="showDeleteModal = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Add this to your main component or include it here
    function deletePatientModal() {
        return {
            showDeleteModal: false,
            userToDelete: null,
            deleting: false,

            confirmDelete(user) {
                this.userToDelete = user;
                this.showDeleteModal = true;
            },

            async confirmDeleteAction() {
                this.deleting = true;
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const response = await fetch(`/reception/patients/${this.userToDelete.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.showToast('Patient deleted successfully!', 'success');
                        this.closeDeleteModal();
                        if (typeof this.fetchPatients === 'function') {
                            await this.fetchPatients();
                        }
                        if (typeof this.fetchStats === 'function') {
                            await this.fetchStats();
                        }
                    } else {
                        this.showToast(data.message || 'Failed to delete patient', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting patient:', error);
                    this.showToast('Network error. Please try again.', 'error');
                } finally {
                    this.deleting = false;
                }
            },

            closeDeleteModal() {
                this.showDeleteModal = false;
                this.userToDelete = null;
            },

            showToast(message, type) {
                if (window.showNotification) {
                    window.showNotification(message, type);
                } else if (window.toastr) {
                    window.toastr[type](message);
                } else {
                    alert(message);
                }
            },

            getAvatarColor(name, gender) {
                const genderColors = {
                    'male': 'bg-gradient-to-br from-blue-400 to-blue-600',
                    'female': 'bg-gradient-to-br from-pink-400 to-pink-600',
                    'other': 'bg-gradient-to-br from-purple-400 to-purple-600'
                };
                
                if (gender && genderColors[gender]) {
                    return genderColors[gender];
                }
                
                const colors = [
                    'bg-gradient-to-br from-blue-400 to-blue-600',
                    'bg-gradient-to-br from-green-400 to-green-600',
                    'bg-gradient-to-br from-purple-400 to-purple-600',
                    'bg-gradient-to-br from-pink-400 to-pink-600'
                ];
                const index = name ? name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0) % colors.length : 0;
                return colors[index];
            }
        };
    }
</script>