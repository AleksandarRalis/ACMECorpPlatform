<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-lg p-8 bg-white rounded shadow">
      <h2 class="text-2xl font-bold mb-6 text-center">Register for ACME Corp CSR</h2>
      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="name">Full Name</label>
          <input 
            v-model="name" 
            id="name" 
            type="text" 
            required 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.name ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.name" class="text-red-500 text-sm mt-1">{{ fieldErrors.name }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="email">Email</label>
          <input 
            v-model="email" 
            id="email" 
            type="email" 
            required 
            autocomplete="email" 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.email ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.email" class="text-red-500 text-sm mt-1">{{ fieldErrors.email }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="department">Department</label>
          <input 
            v-model="department" 
            id="department" 
            type="text" 
            required 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.department ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.department" class="text-red-500 text-sm mt-1">{{ fieldErrors.department }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="position">Position</label>
          <input 
            v-model="position" 
            id="position" 
            type="text" 
            required 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.position ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.position" class="text-red-500 text-sm mt-1">{{ fieldErrors.position }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="phone">Phone</label>
          <input 
            v-model="phone" 
            id="phone" 
            type="text" 
            required 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.phone ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.phone" class="text-red-500 text-sm mt-1">{{ fieldErrors.phone }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="password">Password</label>
          <input 
            v-model="password" 
            id="password" 
            type="password" 
            required 
            autocomplete="new-password" 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.password ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.password" class="text-red-500 text-sm mt-1">{{ fieldErrors.password }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="password_confirmation">Confirm Password</label>
          <input 
            v-model="password_confirmation" 
            id="password_confirmation" 
            type="password" 
            required 
            autocomplete="new-password" 
            :class="[
              'w-full px-3 py-2 border rounded focus:outline-none focus:ring',
              fieldErrors.password_confirmation ? 'border-red-500 focus:border-red-500' : 'focus:border-blue-300'
            ]" 
          />
          <p v-if="fieldErrors.password_confirmation" class="text-red-500 text-sm mt-1">{{ fieldErrors.password_confirmation }}</p>
        </div>
        <div v-if="error" class="mb-4 text-red-600 text-sm">{{ error }}</div>
        <div v-if="success" class="mb-4 text-green-600 text-sm bg-green-50 p-3 rounded">
          ✅ Registration successful! Redirecting to login page...
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition" :disabled="loading || success">
          <span v-if="loading">Registering...</span>
          <span v-else-if="success">Success!</span>
          <span v-else>Register</span>
        </button>
      </form>
      <div class="mt-4 text-center text-sm">
        Already have an account?
        <router-link to="/login" class="text-blue-600 hover:underline">Login</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
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
  error.value = '';
  fieldErrors.value = {};
  success.value = false;
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
      const errorMessages = Object.values(errors).flat();
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
</script> 