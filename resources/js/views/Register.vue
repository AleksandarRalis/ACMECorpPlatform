<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-lg p-8 bg-white rounded shadow">
      <h2 class="text-2xl font-bold mb-6 text-center">Register for ACME Corp CSR</h2>
      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="name">Full Name</label>
          <input v-model="name" id="name" type="text" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="email">Email</label>
          <input v-model="email" id="email" type="email" required autocomplete="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="employee_id">Employee ID</label>
          <input v-model="employee_id" id="employee_id" type="text" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="department">Department</label>
          <input v-model="department" id="department" type="text" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="position">Position</label>
          <input v-model="position" id="position" type="text" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="phone">Phone</label>
          <input v-model="phone" id="phone" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="bio">Bio</label>
          <textarea v-model="bio" id="bio" rows="2" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="password">Password</label>
          <input v-model="password" id="password" type="password" required autocomplete="new-password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="password_confirmation">Confirm Password</label>
          <input v-model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div v-if="error" class="mb-4 text-red-600 text-sm">{{ error }}</div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition" :disabled="loading">
          <span v-if="loading">Registering...</span>
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

const name = ref('');
const email = ref('');
const employee_id = ref('');
const department = ref('');
const position = ref('');
const phone = ref('');
const bio = ref('');
const password = ref('');
const password_confirmation = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();

async function handleRegister() {
  error.value = '';
  loading.value = true;
  try {
    // TODO: Replace with real API call
    // await registerApi({ name, email, employee_id, department, position, phone, bio, password, password_confirmation })
    await new Promise(r => setTimeout(r, 1000));
    // On success, redirect to login
    router.push('/login');
  } catch (e) {
    error.value = 'Registration failed. Please check your input or try again.';
  } finally {
    loading.value = false;
  }
}
</script> 