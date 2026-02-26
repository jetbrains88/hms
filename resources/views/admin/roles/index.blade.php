{{-- resources/views/admin/roles/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Role Management')
@section('page-description', 'Manage user roles and permissions')

@section('content')
    <div class="p-6">
        {{--        <div class="flex justify-between items-center mb-6">--}}
        {{--            <div>--}}
        {{--                <h1 class="text-2xl font-bold text-gray-900">Role Management</h1>--}}
        {{--                <p class="text-gray-600 mt-1">Create and manage user roles with specific permissions</p>--}}
        {{--            </div>--}}
        {{--            <button type="button" onclick="openRoleModal()"--}}
        {{--                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm flex items-center gap-2">--}}
        {{--                <i class="fas fa-plus"></i>--}}
        {{--                Add New Role--}}
        {{--            </button>--}}
        {{--        </div>--}}

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Roles</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $roles->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-tag text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Total Permissions</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $permissions->flatten()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Permission Groups</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ count($permissions) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-layer-group text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white grid grid-cols-1 justify-between  md:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">All Roles</h2>
                    <p class="text-gray-600 text-sm mt-1">Manage existing roles and their permissions</p>
                </div>
                <div class="flex">
                    <button type="button" onclick="openRoleModal()"
                            class="ml-auto bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-4 py-2.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add New Role
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Display Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permissions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created At
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach ($roles as $role)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-medium">#{{ $role->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class=" mr-2 text-gray-500"
                                           :class="getRoleIconClass('{{ $role->name }}')"></i>
                                        {{ $role->name }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $role->display_name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-md">
                                    @foreach ($role->permissions as $permission)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-1 text-xs"></i>
                                                {{ $permission->display_name }}
                                            </span>
                                    @endforeach
                                    @if ($role->permissions->isEmpty())
                                        <span class="text-gray-400 text-sm italic">No permissions assigned</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500">{{ $role->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <button onclick="editRole({{ $role->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit mr-1.5 text-sm"></i>
                                        Edit
                                    </button>
                                    @if ($role->id > 1)
                                        {{-- Don't allow deletion of admin role --}}
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                                <i class="fas fa-trash-alt mr-1.5 text-sm"></i>
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm italic">System Role</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if ($roles->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-tag text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No roles found</h3>
                    <p class="text-gray-600 mb-6">Get started by creating your first role</p>
                    <button onclick="openRoleModal()"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Role
                    </button>
                </div>
            @endif
        </div>

        <!-- Permissions Reference -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Available Permissions</h2>
                        <p class="text-gray-600 text-sm mt-1">All permissions grouped by category</p>
                    </div>
                    <span class="text-sm text-gray-500">{{ $permissions->flatten()->count() }} permissions</span>
                </div>
            </div>
            <div class="p-6">
                @foreach ($permissions as $group => $groupPermissions)
                    <div class="mb-8 last:mb-0">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-folder text-indigo-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $group)) }}
                            </h3>
                            <span class="ml-3 px-2.5 py-0.5 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                {{ count($groupPermissions) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach ($groupPermissions as $permission)
                                <div
                                    class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all bg-white">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <i class="fas fa-shield-alt text-blue-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="font-medium text-gray-900">{{ $permission->display_name }}</h4>
                                            <p class="text-sm text-gray-500 font-mono mt-1 bg-gray-50 px-2 py-1 rounded">
                                                {{ $permission->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add/Edit Role Modal -->
    <div id="roleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-tag text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900" id="roleModalTitle">Add New Role</h3>
                        </div>
                        <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="roleForm" method="POST" class="px-6 pt-6 pb-8">
                    @csrf
                    <input type="hidden" id="roleId" name="id">

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                <i class="fas fa-tag mr-2 text-gray-400"></i>
                                Role Name (Slug)
                            </label>
                            <input type="text" name="name" id="roleName"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="e.g., 'supervisor', 'manager'" required>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Lowercase, no spaces, use underscores. This is used internally.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i>
                                Display Name
                            </label>
                            <input type="text" name="display_name" id="roleDisplayName"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="e.g., 'Supervisor', 'Department Manager'" required>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                This name will be shown to users in the interface.
                            </p>
                        </div>

                        <!-- Permissions Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-3">
                                <i class="fas fa-shield-alt mr-2 text-gray-400"></i>
                                Permissions
                            </label>
                            <div class="border border-gray-300 rounded-lg overflow-hidden">
                                <div class="max-h-96 overflow-y-auto p-4 bg-gray-50">
                                    @foreach ($permissions as $group => $groupPermissions)
                                        <div class="mb-6 last:mb-0">
                                            <div class="flex items-center mb-3">
                                                <input type="checkbox"
                                                       class="permission-group-toggle rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                       data-group="{{ $group }}">
                                                <h4 class="font-semibold text-gray-900 ml-2">
                                                    {{ ucfirst(str_replace('_', ' ', $group)) }}</h4>
                                                <span
                                                    class="ml-2 text-xs text-gray-500 bg-gray-200 px-2 py-0.5 rounded">
                                                    {{ count($groupPermissions) }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 ml-6">
                                                @foreach ($groupPermissions as $permission)
                                                    <label
                                                        class="flex items-center space-x-3 p-3 hover:bg-white rounded-lg border border-gray-200 cursor-pointer transition-all">
                                                        <input type="checkbox" name="permissions[]"
                                                               value="{{ $permission->id }}"
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 permission-checkbox"
                                                               data-group="{{ $group }}">
                                                        <div class="flex-1">
                                                            <span
                                                                class="block font-medium text-gray-900">{{ $permission->display_name }}</span>
                                                            <span
                                                                class="text-xs text-gray-500 font-mono">{{ $permission->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="bg-gray-100 px-4 py-3 border-t border-gray-300">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600" id="selectedPermissionsCount">0 permissions
                                            selected</span>
                                        <button type="button" onclick="selectAllPermissions()"
                                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Select All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3 border-t border-gray-200 pt-6">
                        <button type="button" onclick="closeRoleModal()"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="submit" id="roleSubmitBtn"
                                class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all font-medium flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            <span id="submitBtnText">Save Role</span>
                            <div id="roleLoadingSpinner" class="ml-2 hidden">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Add CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let currentRoleId = null;

        function openRoleModal(roleId = null) {
            const modal = document.getElementById('roleModal');
            const form = document.getElementById('roleForm');
            const title = document.getElementById('roleModalTitle');
            const submitBtnText = document.getElementById('submitBtnText');

            // Reset form
            form.reset();
            currentRoleId = roleId;

            // Remove any existing method spoofing
            const existingMethod = form.querySelector('input[name="_method"]');
            if (existingMethod) existingMethod.remove();

            // Uncheck all permission checkboxes
            document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
            updateSelectedPermissionsCount();

            if (roleId) {
                // Set edit mode
                title.textContent = 'Edit Role';
                submitBtnText.textContent = 'Update Role';

                // Add method spoofing for PUT
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                // Set form action
                form.action = `/admin/roles/${roleId}`;

                // Fetch role data via AJAX
                fetchRoleData(roleId);
            } else {
                // Set add mode
                title.textContent = 'Add New Role';
                submitBtnText.textContent = 'Save Role';
                form.action = "{{ route('admin.roles.store') }}";
                document.getElementById('roleId').value = '';
            }

            modal.classList.remove('hidden');
            modal.classList.add('block');
            document.body.classList.add('overflow-hidden');
        }

        function fetchRoleData(roleId) {
            const submitBtn = document.getElementById('roleSubmitBtn');
            const spinner = document.getElementById('roleLoadingSpinner');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');

            fetch(`/admin/roles/${roleId}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Populate form fields
                    document.getElementById('roleName').value = data.name || '';
                    document.getElementById('roleDisplayName').value = data.display_name || '';
                    document.getElementById('roleId').value = data.id || '';

                    // Check permission checkboxes
                    if (data.permissions) {
                        data.permissions.forEach(permissionId => {
                            const checkbox = document.querySelector(
                                `input[name="permissions[]"][value="${permissionId}"]`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }

                    updateSelectedPermissionsCount();
                    updateGroupCheckboxes();

                    // Restore button
                    submitBtn.disabled = false;
                    spinner.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error fetching role data:', error);
                    showNotification('Error loading role data. Please try again.', 'error', 'Error');

                    // Restore button
                    submitBtn.disabled = false;
                    spinner.classList.add('hidden');
                });
        }

        function editRole(roleId) {
            openRoleModal(roleId);
        }

        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.remove('block');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('roleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const url = form.action;
            const method = form.querySelector('input[name="_method"]') ? 'PUT' : 'POST';

            // DEBUG: Log form data before submission
            console.log('Form action:', url);
            console.log('Form method:', method);

            // Create FormData properly
            const formData = new FormData(form);

            // Add checked permissions manually (important for checkboxes)
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
            const permissions = Array.from(permissionCheckboxes).map(cb => cb.value);

            // Build data object for JSON
            const data = {
                name: document.getElementById('roleName').value,
                display_name: document.getElementById('roleDisplayName').value,
                permissions: Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
                    .map(cb => parseInt(cb.value))
            };

            console.log('Sending JSON data:', data);

            // Debug: Check what's in the form
            console.log('Form name field value:', form.elements['name']?.value);
            console.log('Form display_name field value:', form.elements['display_name']?.value);
            console.log('Form has elements:', Array.from(form.elements).map(el => ({
                name: el.name,
                value: el.value,
                type: el.type
            })));

            const submitBtn = document.getElementById('roleSubmitBtn');
            const spinner = document.getElementById('roleLoadingSpinner');

            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');

            fetch(url, {
                method: method,
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json().then(data => ({
                        status: response.status,
                        body: data
                    }));
                })
                .then(({
                           status,
                           body
                       }) => {
                    console.log('Response body:', body);

                    if (status >= 200 && status < 300) {
                        if (body.success) {
                            showNotification(body.message || 'Role saved successfully!', 'success');
                            closeRoleModal();
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showNotification(body.message || 'Failed to save role.', 'error');
                            resetButton();
                        }
                    } else {
                        if (body.errors) {
                            let errorMessages = '';
                            Object.values(body.errors).forEach(errors => {
                                errors.forEach(error => errorMessages += error + '\n');
                            });
                            showNotification(errorMessages, 'error');
                        } else {
                            showNotification(body.message || 'An error occurred.', 'error');
                        }
                        resetButton();
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                    resetButton();
                });

            function resetButton() {
                submitBtn.disabled = false;
                spinner.classList.add('hidden');
            }
        });

        // Permission selection helpers
        function updateSelectedPermissionsCount() {
            const selectedCount = document.querySelectorAll('input[name="permissions[]"]:checked').length;
            const totalCount = document.querySelectorAll('input[name="permissions[]"]').length;
            document.getElementById('selectedPermissionsCount').textContent =
                `${selectedCount} of ${totalCount} permissions selected`;
        }

        function selectAllPermissions() {
            const allCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);

            allCheckboxes.forEach(cb => cb.checked = !allChecked);
            updateSelectedPermissionsCount();
            updateGroupCheckboxes();
        }

        function updateGroupCheckboxes() {
            document.querySelectorAll('.permission-group-toggle').forEach(groupToggle => {
                const group = groupToggle.dataset.group;
                const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(groupCheckboxes).some(cb => cb.checked);

                groupToggle.checked = allChecked;
                groupToggle.indeterminate = someChecked && !allChecked;
            });
        }

        function getRoleIconClass(roleName) {
            const roleMap = {
                'admin': 'fas fa-shield-alt',
                'doctor': 'fas fa-stethoscope',
                'nurse': 'fas fa-user-nurse',
                'pharmacy': 'fas fa-pills',
                'lab': 'fas fa-flask',
                'reception': 'fas fa-user-plus'
            };
            return roleMap[roleName?.toLowerCase()] || 'fas fa-user';
        }

        // Group toggle functionality
        document.querySelectorAll('.permission-group-toggle').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const group = this.dataset.group;
                const groupCheckboxes = document.querySelectorAll(
                    `.permission-checkbox[data-group="${group}"]`);
                groupCheckboxes.forEach(cb => cb.checked = this.checked);
                updateSelectedPermissionsCount();
            });
        });

        // Individual checkbox change
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                updateSelectedPermissionsCount();
                updateGroupCheckboxes();
            });
        });

        // Handle delete form submission with AJAX
        document.querySelectorAll('form[action*="destroy"]').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
                    const form = this;
                    const url = form.action;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification(data.message || 'Role deleted successfully!',
                                    'success', 'Success');
                                // Reload the page after short delay
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showNotification(data.message || 'Failed to delete role.', 'error',
                                    'Error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('An error occurred while deleting the role.', 'error',
                                'Error');
                        });
                }
            });
        });

        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeRoleModal();
        });

        // Close modal when clicking outside
        document.getElementById('roleModal')?.addEventListener('click', function (e) {
            if (e.target.id === 'roleModal') closeRoleModal();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            updateSelectedPermissionsCount();
            updateGroupCheckboxes();

            // Make sure notification function exists
            if (typeof showNotification === 'undefined') {
                console.warn('Notification system not loaded');
            }
        });
    </script>
@endpush
