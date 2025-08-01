<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Login to ACME Corp CSR</h2>
      
      <form @submit.prevent="handleLogin">
        <!-- Error Display -->
        <div v-if="error" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Login Error</h3>
              <div class="mt-2 text-sm text-red-700">
                <p>{{ error }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
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
              placeholder="Enter your email"
            />
            <p v-if="fieldErrors.email" class="mt-1 text-sm text-red-600">{{ fieldErrors.email }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="password">Password</label>
            <input 
              v-model="password" 
              id="password" 
              type="password" 
              autocomplete="current-password" 
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.password ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter your password"
            />
            <p v-if="fieldErrors.password" class="mt-1 text-sm text-red-600">{{ fieldErrors.password }}</p>
          </div>
        </div>

        <button 
          type="submit" 
          :disabled="loading"
          class="w-full mt-6 bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          <span v-if="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Logging in...
          </span>
          <span v-else>Login</span>
        </button>
      </form>
      
      <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <router-link to="/register" class="text-blue-600 hover:text-blue-500 font-medium">Register</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const email = ref('');
const password = ref('');
const error = ref('');
const fieldErrors = ref({});
const loading = ref(false);
const router = useRouter();
const auth = useAuthStore();

async function handleLogin() {
  // Clear previous errors
  error.value = '';
  fieldErrors.value = {};
  
  // Validate form
  const errors = validateForm();
  if (Object.keys(errors).length > 0) {
    fieldErrors.value = errors;
    return;
  }
  
  loading.value = true;
  try {
    const response = await axios.post('/api/auth/login', {
      email: email.value,
      password: password.value
    });
    const { token, user } = response.data;
    auth.setToken(token);
    auth.setUser(user);
    router.push('/');
  } catch (e) {
    if (e.response && e.response.data && e.response.data.message) {
      error.value = e.response.data.message;
    } else {
      error.value = 'Invalid credentials or server error.';
    }
  } finally {
    loading.value = false;
  }
}

// Form validation
function validateForm() {
  const errors = {};
  
  if (!email.value.trim()) {
    errors.email = 'Email is required';
  } else if (!isValidEmail(email.value)) {
    errors.email = 'Please enter a valid email address';
  }
  
  if (!password.value) {
    errors.password = 'Password is required';
  }
  
  return errors;
}

// Email validation helper
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Watch for form changes to clear field errors
watch(() => email.value, () => {
  if (fieldErrors.value.email) {
    delete fieldErrors.value.email;
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
</script> 