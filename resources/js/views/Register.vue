<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Register for ACME Corp CSR</h2>
      
      <form @submit.prevent="handleRegister">
        <!-- Error Display -->
        <div v-if="error" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Registration Error</h3>
              <div class="mt-2 text-sm text-red-700">
                <p>{{ error }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Display -->
        <div v-if="success" class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-green-800">Registration Successful!</h3>
              <div class="mt-2 text-sm text-green-700">
                <p>Redirecting to login page...</p>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="name">Full Name</label>
            <input 
              v-model="name" 
              id="name" 
              type="text" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.name ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your full name"
            />
            <p v-if="fieldErrors.name" class="mt-1 text-sm text-red-600">{{ fieldErrors.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
            <input 
              v-model="email" 
              id="email" 
              type="email" 
              autocomplete="email" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.email ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your email address"
            />
            <p v-if="fieldErrors.email" class="mt-1 text-sm text-red-600">{{ fieldErrors.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="department">Department</label>
            <input 
              v-model="department" 
              id="department" 
              type="text" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.department ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your department"
            />
            <p v-if="fieldErrors.department" class="mt-1 text-sm text-red-600">{{ fieldErrors.department }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="position">Position</label>
            <input 
              v-model="position" 
              id="position" 
              type="text" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.position ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your position"
            />
            <p v-if="fieldErrors.position" class="mt-1 text-sm text-red-600">{{ fieldErrors.position }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="phone">Phone</label>
            <input 
              v-model="phone" 
              id="phone" 
              type="text" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.phone ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your phone number"
            />
            <p v-if="fieldErrors.phone" class="mt-1 text-sm text-red-600">{{ fieldErrors.phone }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="password">Password</label>
            <input 
              v-model="password" 
              id="password" 
              type="password" 
              autocomplete="new-password" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.password ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your password"
            />
            <p v-if="fieldErrors.password" class="mt-1 text-sm text-red-600">{{ fieldErrors.password }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="password_confirmation">Confirm Password</label>
            <input 
              v-model="password_confirmation" 
              id="password_confirmation" 
              type="password" 
              autocomplete="new-password" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.password_confirmation ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Confirm your password"
            />
            <p v-if="fieldErrors.password_confirmation" class="mt-1 text-sm text-red-600">{{ fieldErrors.password_confirmation }}</p>
          </div>
        </div>

        <button 
          type="submit" 
          :disabled="loading || success"
          class="w-full mt-6 bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          <span v-if="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Registering...
          </span>
          <span v-else-if="success">Success!</span>
          <span v-else>Register</span>
        </button>
      </form>
      <div class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <router-link to="/login" class="text-blue-600 hover:text-blue-500 font-medium">Login</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const name = ref('');
const email = ref('');
const department = ref('');
const position = ref('');
const phone = ref('');
const password = ref('');
const password_confirmation = ref('');
const error = ref('');
const fieldErrors = ref({});
const loading = ref(false);
const success = ref(false);
const router = useRouter();

async function handleRegister() {
  // Clear previous errors
  error.value = '';
  fieldErrors.value = {};
  success.value = false;
  
  // Validate form
  const errors = validateForm();
  if (Object.keys(errors).length > 0) {
    fieldErrors.value = errors;
    return;
  }
  
  loading.value = true;
  
  try {
    const response = await axios.post('/api/auth/register', {
      name: name.value,
      email: email.value,
      department: department.value,
      position: position.value,
      phone: phone.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });

    // Registration successful
    console.log('Registration successful:', response.data);
    
    // Show success state
    success.value = true;
    
    // Redirect to login page after 2 seconds
    setTimeout(() => {
      router.push('/login');
    }, 2000);
    
  } catch (err) {
    console.error('Registration error:', err);
    
    if (err.response?.data?.errors) {
      // Handle validation errors
      const errors = err.response.data.errors;
      fieldErrors.value = {};
      
      // Set field-specific errors
      Object.keys(errors).forEach(field => {
        fieldErrors.value[field] = errors[field][0]; // Take first error message for each field
      });
      
      // Set general error message
      error.value = 'Please fix the errors below.';
    } else if (err.response?.data?.message) {
      // Handle other API errors
      error.value = err.response.data.message;
    } else {
      // Handle network or other errors
      error.value = 'Registration failed. Please check your connection and try again.';
    }
  } finally {
    loading.value = false;
  }
}

// Form validation
function validateForm() {
  const errors = {};
  
  if (!name.value.trim()) {
    errors.name = 'Full name is required';
  }
  
  if (!email.value.trim()) {
    errors.email = 'Email is required';
  } else if (!isValidEmail(email.value)) {
    errors.email = 'Please enter a valid email address';
  }
  
  if (!department.value.trim()) {
    errors.department = 'Department is required';
  }
  
  if (!position.value.trim()) {
    errors.position = 'Position is required';
  }
  
  if (!phone.value.trim()) {
    errors.phone = 'Phone number is required';
  }
  
  if (!password.value) {
    errors.password = 'Password is required';
  } else if (password.value.length < 8) {
    errors.password = 'Password must be at least 8 characters long';
  }
  
  if (!password_confirmation.value) {
    errors.password_confirmation = 'Please confirm your password';
  } else if (password.value !== password_confirmation.value) {
    errors.password_confirmation = 'Passwords do not match';
  }
  
  return errors;
}

// Email validation helper
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Watch for form changes to clear field errors
watch(() => name.value, () => {
  if (fieldErrors.value.name) {
    delete fieldErrors.value.name;
  }
  if (error.value) {
    error.value = '';
  }
});

watch(() => email.value, () => {
  if (fieldErrors.value.email) {
    delete fieldErrors.value.email;
  }
  if (error.value) {
    error.value = '';
  }
});

watch(() => department.value, () => {
  if (fieldErrors.value.department) {
    delete fieldErrors.value.department;
  }
  if (error.value) {
    error.value = '';
  }
});

watch(() => position.value, () => {
  if (fieldErrors.value.position) {
    delete fieldErrors.value.position;
  }
  if (error.value) {
    error.value = '';
  }
});

watch(() => phone.value, () => {
  if (fieldErrors.value.phone) {
    delete fieldErrors.value.phone;
  }
  if (error.value) {
    error.value = '';
  }
});

watch(() => password.value, () => {
  if (fieldErrors.value.password) {
    delete fieldErrors.value.password;
  }
  if (error.value) {
    error.value = '';
  }
});

watch(() => password_confirmation.value, () => {
  if (fieldErrors.value.password_confirmation) {
    delete fieldErrors.value.password_confirmation;
  }
  if (error.value) {
    error.value = '';
  }
});
</script> 