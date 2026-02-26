{{-- resources/views/admin/permissions/index.blade.php --}}
<x-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Permission Management</h1>
            <button type="button" onclick="openPermissionModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add New Permission
            </button>
        </div>

        <!-- Permissions by Group -->
        <div class="space-y-8">
            @foreach($permissions as $group => $groupPermissions)
            <div class="bg-white rounded-lg shadow">
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 class="text-xl font-bold text-gray-700">{{ ucfirst($group) }}</h2>
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                        {{ count($groupPermissions) }} permissions
                    </span>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($groupPermissions as $permission)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow"
                            data-permission-id="{{ $permission->id }}">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-800" data-display-name="{{ $permission->display_name }}">
                                        {{ $permission->display_name }}
                                    </h3>
                                    <code class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded" data-name="{{ $permission->name }}">
                                        {{ $permission->name }}
                                    </code>
                                    <div class="text-xs text-gray-400 mt-1" data-group="{{ $permission->group }}">
                                        Group: {{ $permission->group }}
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editPermission({{ $permission->id }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Delete this permission?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 mt-3">
                                Created: {{ $permission->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add/Edit Permission Modal -->
        <div id="permissionModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold" id="permissionModalTitle">Add New Permission</h2>
                    <button onclick="closePermissionModal()" class="text-gray-500 hover:text-gray-700">
                        &times;
                    </button>
                </div>
                <form id="permissionForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="permissionId" name="id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Permission Name (Slug)</label>
                            <input type="text" name="name" id="permissionName"
                                class="w-full border rounded p-2 font-mono"
                                placeholder="e.g., 'users.create', 'patients.view'" required>
                            <p class="text-xs text-gray-500 mt-1">Lowercase, use dots for namespacing</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Group</label>
                            <select name="group" id="permissionGroup" class="w-full border rounded p-2" required>
                                <option value="">Select Group</option>
                                <option value="user">User</option>
                                <option value="patient">Patient</option>
                                <option value="doctor">Doctor</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="appointment">Appointment</option>
                                <option value="report">Report</option>
                                <option value="system">System</option>
                                <option value="admin">Admin</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Group permissions logically</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Display Name</label>
                            <input type="text" name="display_name" id="permissionDisplayName"
                                class="w-full border rounded p-2"
                                placeholder="e.g., 'Create Users', 'View Patient Records'" required>
                            <p class="text-xs text-gray-500 mt-1">Human-readable name for display</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closePermissionModal()"
                            class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function openPermissionModal(permissionId = null) {
            const modal = document.getElementById('permissionModal');
            const form = document.getElementById('permissionForm');
            const title = document.getElementById('permissionModalTitle');

            // Reset form
            form.reset();

            // Remove any existing method spoofing
            const existingMethod = form.querySelector('input[name="_method"]');
            if (existingMethod) {
                existingMethod.remove();
            }

            if (permissionId) {
                // Set edit mode
                title.textContent = 'Edit Permission';

                // Add method spoofing for PUT
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                // Set form action with permission ID - FIXED: Use dynamic URL
                form.action = `/admin/permissions/${permissionId}`;

                // Fetch permission data via AJAX
                fetchPermissionData(permissionId);
            } else {
                // Set add mode
                title.textContent = 'Add New Permission';
                form.action = "{{ route('admin.permissions.store') }}";
                document.getElementById('permissionId').value = '';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function fetchPermissionData(permissionId) {
            // Show loading state
            const submitBtn = document.querySelector('#permissionForm button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Loading...';
            submitBtn.disabled = true;

            fetch(`/admin/permissions/${permissionId}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Populate form fields
                    document.getElementById('permissionName').value = data.name || '';
                    document.getElementById('permissionGroup').value = data.group || '';
                    document.getElementById('permissionDisplayName').value = data.display_name || '';
                    document.getElementById('permissionId').value = data.id || '';

                    // Restore button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching permission data:', error);

                    // Try to get data from DOM as fallback
                    populatePermissionFormFromDOM(permissionId);

                    // Restore button even on error
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        }

        function populatePermissionFormFromDOM(permissionId) {
            // Find the permission card with this ID
            const permissionCard = document.querySelector(`[data-permission-id="${permissionId}"]`);

            if (permissionCard) {
                const nameElement = permissionCard.querySelector('[data-name]');
                const groupElement = permissionCard.querySelector('[data-group]');
                const displayNameElement = permissionCard.querySelector('[data-display-name]');

                if (nameElement) {
                    document.getElementById('permissionName').value = nameElement.getAttribute('data-name') || '';
                }

                if (groupElement) {
                    document.getElementById('permissionGroup').value = groupElement.getAttribute('data-group') || '';
                }

                if (displayNameElement) {
                    document.getElementById('permissionDisplayName').value = displayNameElement.getAttribute('data-display-name') || '';
                }

                document.getElementById('permissionId').value = permissionId;
            }
        }

        function editPermission(permissionId) {
            openPermissionModal(permissionId);
        }

        function closePermissionModal() {
            const modal = document.getElementById('permissionModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        // Handle form submission with AJAX
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const url = form.action;
            const method = form.querySelector('input[name="_method"]') ? 'PUT' : 'POST';

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Network response was not ok');
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert(data.message || 'Permission saved successfully!');

                        // Close modal
                        closePermissionModal();

                        // Reload the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        alert(data.message || 'Failed to save permission.');
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Add click event listener to Edit buttons
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('[onclick^="editPermission"]');
            editButtons.forEach(button => {
                // Extract permission ID from onclick attribute
                const onclickValue = button.getAttribute('onclick');
                const match = onclickValue.match(/editPermission\((\d+)\)/);
                if (match) {
                    const permissionId = match[1];
                    button.removeAttribute('onclick');
                    button.addEventListener('click', function() {
                        editPermission(permissionId);
                    });
                }
            });

            // Add click event listener to Add New Permission button
            const addButton = document.querySelector('[onclick="openPermissionModal()"]');
            if (addButton) {
                addButton.removeAttribute('onclick');
                addButton.addEventListener('click', function() {
                    openPermissionModal();
                });
            }

            // Add click event listener to close button
            const closeButton = document.querySelector('[onclick="closePermissionModal()"]');
            if (closeButton) {
                closeButton.removeAttribute('onclick');
                closeButton.addEventListener('click', closePermissionModal);
            }

            // Close modal when clicking outside
            document.getElementById('permissionModal').addEventListener('click', function(e) {
                if (e.target.id === 'permissionModal') {
                    closePermissionModal();
                }
            });
        });
    </script>
</x-layout>