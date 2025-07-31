<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <!-- Navigation for authenticated users -->
    <nav v-if="auth.token" class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">ACME Corp CSR Platform</h1>
          </div>
          <div class="flex items-center space-x-4">
            <router-link to="/campaigns" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Campaigns
            </router-link>
            <router-link to="/campaigns/my" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              My Campaigns
            </router-link>
            <router-link 
              v-if="isAdmin" 
              to="/admin" 
              class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              Admin Panel
            </router-link>
            <button @click="handleLogout" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Simple header for unauthenticated users -->
    <header v-else class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">ACME Corp CSR Platform</h1>
          </div>
        </div>
      </div>
    </header>

    <main class="py-6 sm:px-6 lg:px-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';
import { computed } from 'vue';
import axios from 'axios';

const auth = useAuthStore();
const router = useRouter();

// Check if current user is admin
const isAdmin = computed(() => {
  return auth.user && auth.user.role && auth.user.role.name === 'admin';
});

async function handleLogout() {
  try {
    // Call logout API if token exists
    if (auth.token) {
      await axios.post('/api/auth/logout', {}, {
        headers: { 'Authorization': `Bearer ${auth.token}` }
      });
    }
  } catch (error) {
    // Continue with logout even if API call fails
    console.error('Logout API error:', error);
  } finally {
    // Clear local auth state
    auth.logout();
    // Remove axios default header
    delete axios.defaults.headers.common['Authorization'];
    // Redirect to login
    router.push('/login');
  }
}
</script>

<style>
/* Global styles */
</style> 