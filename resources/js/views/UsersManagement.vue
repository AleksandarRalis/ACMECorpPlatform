<template>
  <div>
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
      <p class="text-gray-600 mt-2">Manage all users</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="bg-white rounded-lg shadow-md p-8 text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-4 text-gray-600">Loading users...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Error loading users</h3>
          <div class="mt-2 text-sm text-red-700">
            <p>{{ error }}</p>
          </div>
          <div class="mt-4">
            <button
              @click="fetchUsers"
              class="bg-red-100 text-red-800 px-3 py-2 rounded-md text-sm font-medium hover:bg-red-200"
            >
              Try Again
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div v-else class="bg-white rounded-lg shadow-md">
      <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">All Users ({{ users.length }})</h3>
        <button
          @click="fetchUsers"
          class="text-blue-600 hover:text-blue-800 text-sm font-medium"
        >
          Refresh
        </button>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-sm font-medium text-gray-700">{{ user.name?.charAt(0)?.toUpperCase() }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.employee_id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ user.email }}</div>
                <div class="text-sm text-gray-500">{{ user.phone }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ user.department }}</div>
                <div class="text-sm text-gray-500">{{ user.position }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                    user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                    user.role === 'employee' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
                  ]"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                    user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                  ]"
                >
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button 
                  @click="editUser(user)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button 
                  @click="deleteUser(user)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div v-if="showEditUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
            <button 
              @click="closeEditModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form @submit.prevent="updateUser" class="space-y-4">
            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
              <input
                id="name"
                v-model="editForm.name"
                type="text"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.name ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
                placeholder="Enter user name"
              />
              <p v-if="fieldErrors.name" class="mt-1 text-sm text-red-600">{{ fieldErrors.name }}</p>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input
                id="email"
                v-model="editForm.email"
                type="email"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.email ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
                placeholder="Enter user email"
              />
              <p v-if="fieldErrors.email" class="mt-1 text-sm text-red-600">{{ fieldErrors.email }}</p>
            </div>

            <!-- Employee ID -->
            <div>
              <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
              <input
                id="employee_id"
                v-model="editForm.employee_id"
                type="text"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.employee_id ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
                placeholder="Enter employee ID"
              />
              <p v-if="fieldErrors.employee_id" class="mt-1 text-sm text-red-600">{{ fieldErrors.employee_id }}</p>
            </div>

            <!-- Department -->
            <div>
              <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
              <input
                id="department"
                v-model="editForm.department"
                type="text"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.department ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
                placeholder="Enter department"
              />
              <p v-if="fieldErrors.department" class="mt-1 text-sm text-red-600">{{ fieldErrors.department }}</p>
            </div>

            <!-- Position -->
            <div>
              <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
              <input
                id="position"
                v-model="editForm.position"
                type="text"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.position ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
                placeholder="Enter position"
              />
              <p v-if="fieldErrors.position" class="mt-1 text-sm text-red-600">{{ fieldErrors.position }}</p>
            </div>

            <!-- Phone -->
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
              <input
                id="phone"
                v-model="editForm.phone"
                type="tel"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.phone ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
                placeholder="Enter phone number"
              />
              <p v-if="fieldErrors.phone" class="mt-1 text-sm text-red-600">{{ fieldErrors.phone }}</p>
            </div>

            <!-- Role -->
            <div>
              <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
              <select
                id="role"
                v-model="editForm.role"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.role ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
              >
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
              </select>
              <p v-if="fieldErrors.role" class="mt-1 text-sm text-red-600">{{ fieldErrors.role }}</p>
            </div>

            <!-- Status -->
            <div>
              <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
              <select
                id="is_active"
                v-model="editForm.is_active"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                  fieldErrors.is_active ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                ]"
              >
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
              </select>
              <p v-if="fieldErrors.is_active" class="mt-1 text-sm text-red-600">{{ fieldErrors.is_active }}</p>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password
                <span class="text-xs text-gray-500 font-normal">(leave blank to keep current)</span>
              </label>
              <input
                id="password"
                v-model="editForm.password"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter new password"
              />
              <p class="text-xs text-gray-500 mt-1">Only fill this field if you want to change the password</p>
            </div>

            <!-- API Error Message -->
            <div v-if="editError" class="bg-red-50 border border-red-200 rounded-lg p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">Update Error</h3>
                  <div class="mt-2 text-sm text-red-700">
                    <p>{{ editError }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="editLoading"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="editLoading">Updating...</span>
                <span v-else>Update User</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-4">{{ successMessage }}</h3>
          <div class="mt-4">
            <button
              @click="closeSuccessModal"
              class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              OK
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-4">Delete User</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Are you sure you want to delete "<strong>{{ userToDelete?.name }}</strong>"? This action cannot be undone.
            </p>
          </div>
          <div class="flex justify-center space-x-3 mt-4">
            <button
              @click="closeDeleteModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Cancel
            </button>
            <button
              @click="confirmDelete"
              :disabled="deleteLoading"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="deleteLoading">Deleting...</span>
              <span v-else>Delete</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

// Emits
const emit = defineEmits(['users-updated']);

// Reactive data
const users = ref([]);
const loading = ref(false);
const error = ref('');
const showEditUserModal = ref(false);
const showSuccessModal = ref(false);
const showDeleteModal = ref(false);
const editLoading = ref(false);
const deleteLoading = ref(false);
const editError = ref('');
const fieldErrors = ref({});
const successMessage = ref('');
const userToDelete = ref(null);

// Edit form data
const editForm = ref({
  id: null,
  name: '',
  email: '',
  employee_id: '',
  department: '',
  position: '',
  phone: '',
  role: 'employee',
  is_active: true,
  password: ''
});

// Fetch users from API
const fetchUsers = async () => {
  try {
    loading.value = true;
    error.value = '';
    
    const response = await axios.get('/api/admin/users');
    users.value = response.data.data;
    
  } catch (err) {
    console.error('Error fetching users:', err);
    error.value = err.response?.data?.message || 'Failed to load users. Please try again.';
  } finally {
    loading.value = false;
  }
};



// User actions
const editUser = (user) => {
  // Populate the edit form with user data
  editForm.value = {
    id: user.id,
    name: user.name || '',
    email: user.email || '',
    employee_id: user.employee_id || '',
    department: user.department || '',
    position: user.position || '',
    phone: user.phone || '',
    role: user.role || 'employee',
    is_active: user.is_active !== undefined ? user.is_active : true,
    password: '' // Always empty for security - password is never pre-filled
  };
  
  showEditUserModal.value = true;
  editError.value = '';
};

const closeEditModal = () => {
  showEditUserModal.value = false;
  editForm.value = {
    id: null,
    name: '',
    email: '',
    employee_id: '',
    department: '',
    position: '',
    phone: '',
    role: 'employee',
    is_active: true,
    password: ''
  };
  editError.value = '';
  fieldErrors.value = {};
};

const closeSuccessModal = () => {
  showSuccessModal.value = false;
  successMessage.value = '';
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

const updateUser = async () => {
  // Clear previous errors
  editError.value = '';
  fieldErrors.value = {};
  
  // Validate form
  const errors = validateEditForm();
  if (Object.keys(errors).length > 0) {
    fieldErrors.value = errors;
    return;
  }
  
  try {
    editLoading.value = true;
    
    // Prepare the request data
    const requestData = {
      name: editForm.value.name,
      email: editForm.value.email,
      employee_id: editForm.value.employee_id,
      department: editForm.value.department,
      position: editForm.value.position,
      phone: editForm.value.phone,
      role: editForm.value.role,
      is_active: editForm.value.is_active
    };
    
    // Only include password if it's provided
    if (editForm.value.password && editForm.value.password.trim() !== '') {
      requestData.password = editForm.value.password;
    }
    
    const response = await axios.put(`/api/admin/users/${editForm.value.id}`, requestData);
    
    // Refresh users list
    await fetchUsers();
    
    // Close modal and show success message
    closeEditModal();
    successMessage.value = 'User updated successfully!';
    showSuccessModal.value = true;
    
  } catch (err) {
    console.error('Error updating user:', err);
    editError.value = err.response?.data?.message || 'Failed to update user. Please try again.';
  } finally {
    editLoading.value = false;
  }
};

// Form validation
function validateEditForm() {
  const errors = {};
  
  if (!editForm.value.name.trim()) {
    errors.name = 'Name is required';
  }
  
  if (!editForm.value.email.trim()) {
    errors.email = 'Email is required';
  } else if (!isValidEmail(editForm.value.email)) {
    errors.email = 'Please enter a valid email address';
  }
  
  if (!editForm.value.employee_id.trim()) {
    errors.employee_id = 'Employee ID is required';
  }
  
  if (!editForm.value.department.trim()) {
    errors.department = 'Department is required';
  }
  
  if (!editForm.value.position.trim()) {
    errors.position = 'Position is required';
  }
  
  if (!editForm.value.phone.trim()) {
    errors.phone = 'Phone number is required';
  }
  
  if (!editForm.value.role) {
    errors.role = 'Role is required';
  }
  
  if (editForm.value.is_active === undefined || editForm.value.is_active === null) {
    errors.is_active = 'Status is required';
  }
  
  if (editForm.value.password && editForm.value.password.trim() !== '' && editForm.value.password.length < 8) {
    errors.password = 'Password must be at least 8 characters long';
  }
  
  return errors;
}

// Email validation helper
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

const deleteUser = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  try {
    deleteLoading.value = true;
    await axios.delete(`/api/admin/users/${userToDelete.value.id}`);
    
    // Refresh users list
    await fetchUsers();
    
    // Close delete modal and show success message
    closeDeleteModal();
    successMessage.value = 'User deleted successfully!';
    showSuccessModal.value = true;
    
  } catch (err) {
    console.error('Error deleting user:', err);
    closeDeleteModal();
    if (err.response?.data?.message) {
      successMessage.value = err.response.data.message;
      showSuccessModal.value = true;
    }
  } finally {
    deleteLoading.value = false;
  }
};

// Watch for form changes to clear field errors
watch(() => editForm.value.name, () => {
  if (fieldErrors.value.name) {
    delete fieldErrors.value.name;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.email, () => {
  if (fieldErrors.value.email) {
    delete fieldErrors.value.email;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.employee_id, () => {
  if (fieldErrors.value.employee_id) {
    delete fieldErrors.value.employee_id;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.department, () => {
  if (fieldErrors.value.department) {
    delete fieldErrors.value.department;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.position, () => {
  if (fieldErrors.value.position) {
    delete fieldErrors.value.position;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.phone, () => {
  if (fieldErrors.value.phone) {
    delete fieldErrors.value.phone;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.role, () => {
  if (fieldErrors.value.role) {
    delete fieldErrors.value.role;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.is_active, () => {
  if (fieldErrors.value.is_active) {
    delete fieldErrors.value.is_active;
  }
  if (editError.value) {
    editError.value = '';
  }
});

watch(() => editForm.value.password, () => {
  if (fieldErrors.value.password) {
    delete fieldErrors.value.password;
  }
  if (editError.value) {
    editError.value = '';
  }
});

// Load users on component mount
onMounted(() => {
  fetchUsers();
});
</script> 