<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-md p-8 bg-white rounded shadow">
      <h2 class="text-2xl font-bold mb-6 text-center">Login to ACME Corp CSR</h2>
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="email">Email</label>
          <input v-model="email" id="email" type="email" required autocomplete="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="password">Password</label>
          <input v-model="password" id="password" type="password" required autocomplete="current-password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div v-if="error" class="mb-4 text-red-600 text-sm">{{ error }}</div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition" :disabled="loading">
          <span v-if="loading">Logging in...</span>
          <span v-else>Login</span>
        </button>
      </form>
      <div class="mt-4 text-center text-sm">
        Don't have an account?
        <router-link to="/register" class="text-blue-600 hover:underline">Register</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();
const auth = useAuthStore();

async function handleLogin() {
  error.value = '';
  loading.value = true;
  try {
    const response = await axios.post('/api/auth/login', {
      email: email.value,
      password: password.value
    });
    const { token, user } = response.data;
    auth.setToken(token);
    auth.setUser(user);
    // Set axios default header for future requests
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
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
</script> 