@extends('layouts.app')

@section('title', 'User Details')

@section('page-title', 'User: ' . $user->name)

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">Users</a>
    <span class="mx-2 text-slate-300">
        <i class="fas fa-chevron-right text-[10px]"></i>
    </span>
    <span class="text-slate-500 font-medium">{{ $user->name }}</span>
@endsection

@section('content')
<div x-data="userActions()" 
     x-init="init({
        uuid: '{{ $user->uuid }}',
        name: '{{ $user->name }}',
        email: '{{ $user->email }}',
        isActive: {{ $user->is_active ? 'true' : 'false' }},
        roles: {{ json_encode($roles) }},
        branches: {{ json_encode($branches) }}
     })">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-slate-100">
                <div class="flex flex-col items-center text-center">
                    <div class="h-24 w-24 rounded-2xl flex items-center justify-center text-white text-3xl font-black mb-4 shadow-lg shadow-indigo-100 transition-transform hover:scale-105 duration-300"
                        :class="getAvatarColor(userName)">
                        <span x-text="getInitials(userName)"></span>
                    </div>
                    <h2 class="text-xl font-black text-slate-800" x-text="userName"></h2>
                    <p class="text-slate-500 mb-3 font-medium" x-text="userEmail"></p>
                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border shadow-sm"
                        :class="isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100'"
                        x-text="isActive ? 'Active Member' : 'Account Disabled'">
                    </span>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="flex items-center text-slate-600 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center mr-3 shadow-sm">
                            <i class="fas fa-building text-xs"></i>
                        </div>
                        <span class="text-xs font-bold">Main Branch: <span
                                class="text-slate-800 font-black ml-1">{{ $user->branches->where('pivot.is_primary', true)->first()?->name ?? 'N/A' }}</span></span>
                    </div>
                    <div class="flex items-center text-slate-600 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="h-8 w-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center mr-3 shadow-sm">
                            <i class="fas fa-shield-alt text-xs"></i>
                        </div>
                        <span class="text-xs font-bold">Primary Role: 
                            <span class="text-slate-800 font-black ml-1">
                                {{ $user->roles->first()?->display_name ?? 'Staff' }}
                            </span>
                        </span>
                    </div>
                    <div class="flex items-center text-slate-600 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center mr-3 shadow-sm">
                            <i class="fas fa-calendar-check text-xs"></i>
                        </div>
                        <span class="text-xs font-bold">Joined System:
                            <span class="text-slate-800 font-black ml-1">{{ $user->created_at->format('M Y') }}</span></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 space-y-3">
                    <button type="button" @click="editUser({uuid: userUuid})"
                        class="w-full px-5 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 group">
                        <i class="fas fa-user-edit group-hover:rotate-12 transition-transform"></i>
                        Update User Profile
                    </button>

                    <button type="button" @click="toggleUserStatus()"
                        class="w-full px-5 py-3 font-bold rounded-xl transition-all duration-200 border flex items-center justify-center gap-2 group shadow-sm hover:shadow-md"
                        :class="isActive ? 'bg-amber-50 text-amber-700 border-amber-100 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100'">
                        <i class="fas group-hover:scale-110 transition-transform" :class="isActive ? 'fa-user-slash' : 'fa-user-check'"></i>
                        <span x-text="isActive ? 'Deactivate Account' : 'Reactivate Account'"></span>
                    </button>

                    <button type="button" @click="openResetPasswordModal()" 
                        class="w-full px-5 py-3 bg-slate-50 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition-all border border-slate-200 flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-key text-slate-400"></i>
                        Secure Password Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Detailed Stats & Activity -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Roles & Permissions -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i class="fas fa-shield-virus text-indigo-500 text-base"></i>
                        Security Credentials & Roles
                    </h3>
                </div>

                <div class="space-y-4">
                    @foreach ($user->roles as $role)
                        <div class="border border-slate-50 rounded-2xl p-5 hover:bg-slate-50/50 transition-all shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-black text-slate-700 text-base">{{ $role->display_name }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $role->name }}</span>
                                        <span class="h-1 w-1 rounded-full bg-indigo-200"></span>
                                        <span class="text-[10px] font-bold text-indigo-500">{{ $role->permissions->count() }} Linked Permissions</span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-white border border-indigo-50 text-indigo-600 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    {{ $role->branch->name ?? 'System Authorized' }}
                                </span>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach ($role->permissions->take(10) as $permission)
                                    <span class="px-2.5 py-1.5 bg-white border border-slate-100 text-slate-500 rounded-xl text-[10px] font-bold shadow-xs hover:border-indigo-100 hover:text-indigo-600 transition-colors">
                                        {{ $permission->display_name }}
                                    </span>
                                @endforeach
                                @if ($role->permissions->count() > 10)
                                    <span class="px-3 py-1.5 bg-indigo-50 text-indigo-400 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                        +{{ $role->permissions->count() - 10 }} MORE
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t border-slate-50">
                    <a href="{{ route('admin.users.permissions', $user) }}"
                        class="text-xs font-black text-indigo-600 hover:text-indigo-700 flex items-center gap-2 transition-all hover:gap-3">
                        EXPLORE DETAILED SECURITY CLEARANCE <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Assigned Branches -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-slate-100">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-hospital-alt text-rose-500 text-base"></i>
                    Facility Network Assignment
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($user->branches as $branch)
                        <div class="flex items-center p-5 rounded-2xl border bg-white shadow-sm transition-all hover:shadow-md hover:border-indigo-100 group {{ $branch->pivot->is_primary ? 'ring-2 ring-indigo-500/10 border-indigo-100' : 'border-slate-100' }}">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 text-slate-400 flex items-center justify-center text-lg mr-4 group-hover:from-indigo-500 group-hover:to-indigo-600 group-hover:text-white transition-all duration-300">
                                <i class="fas fa-hospital"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-slate-800 text-sm mb-0.5">{{ $branch->name }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-black uppercase tracking-tighter text-slate-400 group-hover:text-indigo-400 transition-colors">{{ $branch->type }}</span>
                                    @if($branch->pivot->is_primary)
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">Primary Hub</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-black text-slate-800">Operational Log history</h3>
                    <a href="{{ route('admin.users.audit', $user) }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest flex items-center gap-2">
                        View Complete Trail <i class="fas fa-history"></i>
                    </a>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($user->auditLogs->take(8) as $log)
                        <div class="flex items-center p-5 hover:bg-slate-50/50 transition-all duration-200">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4 shadow-sm border
                                @if($log->action == 'created') bg-emerald-50 text-emerald-600 border-emerald-100
                                @elseif($log->action == 'updated') bg-sky-50 text-sky-600 border-sky-100
                                @elseif($log->action == 'deleted') bg-rose-50 text-rose-600 border-rose-100
                                @else bg-slate-50 text-slate-600 border-slate-100 @endif">
                                <i class="fas {{ $log->action == 'created' ? 'fa-plus-circle' : ($log->action == 'updated' ? 'fa-edit' : ($log->action == 'deleted' ? 'fa-trash' : 'fa-history')) }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-black text-slate-700">{{ ucfirst($log->action) }} {{ class_basename($log->entity_type) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $log->created_at->diffForHumans() }}</p>
                                    <span class="h-0.5 w-0.5 rounded-full bg-slate-300"></span>
                                    <p class="text-[10px] text-slate-400 font-medium">IP: {{ $log->ip_address }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100/50 px-2 py-1 rounded-lg">{{ $log->branch->name ?? 'System' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <i class="fas fa-fingerprint text-slate-100 text-6xl mb-4"></i>
                            <p class="text-slate-500 font-bold">No activity footprints found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Reusable Modals (Inside x-data scope) -->
    @include('admin.users.modals.add-edit-user-modal')
    @include('admin.users.modals.deactivate-confirmation-modal')
    @include('admin.users.modals.reset-password-modal')
</div>

<script>
    function userActions() {
        return {
            userUuid: '',
            userName: '',
            userEmail: '',
            isActive: false,
            
            // Shared Context
            availableRoles: [],
            availableBranches: [],
            
            // Modal States
            showUserModal: false,
            showDeactivateModal: false,
            showResetPasswordModal: false,
            
            // Process States
            saving: false,
            processingDeactivate: false,
            resettingPassword: false,
            editingUser: false,
            showPassword: false,
            changePassword: false,
            
            // Forms
            userForm: {
                id: null,
                uuid: null,
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role_ids: [],
                branch_ids: [],
                primary_branch_id: null,
                is_active: true
            },
            
            deactivateAction: 'deactivate',
            resetPasswordMethod: 'auto',
            resetPassword: '',
            resetPasswordConfirmation: '',
            sendEmailNotification: true,
            
            // Computed
            get selectedUser() {
                return {
                    uuid: this.userUuid,
                    name: this.userName,
                    email: this.userEmail,
                    is_active: this.isActive
                };
            },

            init(config) {
                if (!config) return;
                this.userUuid = config.uuid || '';
                this.userName = config.name || '';
                this.userEmail = config.email || '';
                this.isActive = !!config.isActive;
                this.availableRoles = config.roles || [];
                this.availableBranches = config.branches || [];
            },

            // Edit Logic
            async editUser(user) {
                this.editingUser = true;
                this.changePassword = false;
                this.showPassword = false;

                try {
                    const response = await fetch(`/admin/users/${user.uuid}/edit`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const userData = await response.json();
                    const fetchedUser = userData.user;

                    this.userForm = {
                        id: fetchedUser.id,
                        uuid: fetchedUser.uuid,
                        name: fetchedUser.name,
                        email: fetchedUser.email,
                        password: '',
                        password_confirmation: '',
                        role_ids: Array.isArray(userData.role_ids) ? userData.role_ids : [],
                        branch_ids: Array.isArray(userData.branch_ids) ? userData.branch_ids : [],
                        primary_branch_id: userData.primary_branch_id,
                        is_active: fetchedUser.is_active
                    };

                    this.showUserModal = true;
                } catch (error) {
                    console.error('Error fetching user details:', error);
                    window.showNotification('Failed to load user details', 'error');
                }
            },

            closeUserModal() {
                this.showUserModal = false;
                this.editingUser = false;
            },

            closeDeactivateModal() {
                this.showDeactivateModal = false;
            },

            closeResetPasswordModal() {
                this.showResetPasswordModal = false;
            },

            async saveUser() {
                this.saving = true;
                try {
                    if (!this.userForm.role_ids || this.userForm.role_ids.length === 0) {
                        window.showNotification('Please select at least one role', 'warning');
                        this.saving = false;
                        return;
                    }

                    const url = `/admin/users/${this.userForm.uuid}`;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const data = {
                        name: this.userForm.name,
                        email: this.userForm.email,
                        is_active: this.userForm.is_active ? 1 : 0,
                        roles: this.userForm.role_ids,
                        branches: this.userForm.branch_ids,
                        primary_branch: this.userForm.primary_branch_id,
                        _token: csrfToken,
                        _method: 'PUT'
                    };

                    if (this.changePassword && this.userForm.password) {
                        data.password = this.userForm.password;
                        data.password_confirmation = this.userForm.password_confirmation;
                    }

                    const response = await fetch(url, {
                        method: 'POST', // Laravel spoofing
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        window.showNotification(result.message, 'success');
                        this.closeUserModal();
                        // Update local state
                        this.userName = this.userForm.name;
                        this.userEmail = this.userForm.email;
                        this.isActive = !!this.userForm.is_active;
                        // Reload to catch complex changes if needed, but smooth update is better
                        // setTimeout(() => window.location.reload(), 800);
                    } else {
                        if (result.errors) {
                            const errorMessages = Object.values(result.errors).flat().join('\n');
                            window.showNotification('Validation Error: ' + errorMessages, 'error');
                        } else {
                            window.showNotification(result.message || 'An error occurred', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error saving user:', error);
                    window.showNotification('Network error. Please try again.', 'error');
                } finally {
                    this.saving = false;
                }
            },

            // Toggles
            toggleUserStatus() {
                this.deactivateAction = this.isActive ? 'deactivate' : 'activate';
                this.showDeactivateModal = true;
            },

            async confirmDeactivateAction() {
                this.processingDeactivate = true;
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch(`/admin/users/${this.userUuid}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        window.showNotification(result.message, 'success');
                        this.isActive = !this.isActive;
                        this.showDeactivateModal = false;
                    } else {
                        window.showNotification(result.message || 'Action failed', 'error');
                    }
                } catch (error) {
                    window.showNotification('Network error occurred', 'error');
                } finally {
                    this.processingDeactivate = false;
                }
            },

            // Password Reset
            openResetPasswordModal() {
                this.resetPasswordMethod = 'auto';
                this.resetPassword = '';
                this.resetPasswordConfirmation = '';
                this.sendEmailNotification = true;
                this.showResetPasswordModal = true;
            },

            async confirmResetPassword() {
                this.resettingPassword = true;
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const data = {
                        method: this.resetPasswordMethod,
                        send_email: this.sendEmailNotification,
                        _token: csrfToken
                    };

                    if (this.resetPasswordMethod === 'manual') {
                        if (this.resetPassword !== this.resetPasswordConfirmation) {
                            window.showNotification('Passwords do not match', 'error');
                            this.resettingPassword = false;
                            return;
                        }
                        data.password = this.resetPassword;
                        data.password_confirmation = this.resetPasswordConfirmation;
                    }

                    const response = await fetch(`/admin/users/${this.userUuid}/reset-password`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        window.showNotification(result.message, 'success');
                        this.showResetPasswordModal = false;
                    } else {
                        window.showNotification(result.message || 'Reset failed', 'error');
                    }
                } catch (error) {
                    window.showNotification('Network error occurred', 'error');
                } finally {
                    this.resettingPassword = false;
                }
            },

            // Utility
            getAvatarColor(name) {
                const colors = [
                    'bg-gradient-to-br from-indigo-500 to-indigo-700',
                    'bg-gradient-to-br from-emerald-500 to-emerald-700',
                    'bg-gradient-to-br from-blue-500 to-blue-700',
                    'bg-gradient-to-br from-violet-500 to-purple-700',
                    'bg-gradient-to-br from-rose-500 to-rose-700'
                ];
                let hash = 0;
                for (let i = 0; i < name.length; i++) {
                    hash = name.charCodeAt(i) + ((hash << 5) - hash);
                }
                const index = Math.abs(hash) % colors.length;
                return colors[index];
            },

            getInitials(name) {
                return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
            },

            getRoleBadgeClass(role) {
                if (role.includes('admin')) return 'bg-rose-50 text-rose-600 border border-rose-100';
                if (role.includes('cmo')) return 'bg-indigo-50 text-indigo-600 border border-indigo-100';
                return 'bg-slate-50 text-slate-600 border border-slate-100';
            },

            getRoleIconClass(role) {
                if (role.includes('admin')) return 'fas fa-shield-alt';
                if (role.includes('cmo')) return 'fas fa-user-md';
                return 'fas fa-user';
            }
        };
    }
</script>
@endsection
