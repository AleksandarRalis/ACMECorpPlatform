<template>
  <div class="px-4 py-6 sm:px-0">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">
        Welcome to ACME Corp CSR Platform
      </h1>
      <p class="text-xl text-gray-600 mb-8">
        Empowering employees to make a difference through community involvement and social initiatives.
      </p>
    </div>

    <!-- Quick Search Section -->
    <div class="max-w-2xl mx-auto mb-12">
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Quick Search Campaigns</h2>
        <div class="flex gap-4">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search for campaigns..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @keyup.enter="goToCampaigns"
          />
          <button
            @click="goToCampaigns"
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Search
          </button>
        </div>
        <div class="mt-3 text-sm text-gray-600 text-center">
          <span class="font-medium">Popular:</span>
          <button
            v-for="tag in popularTags"
            :key="tag"
            @click="searchByTag(tag)"
            class="ml-2 text-blue-600 hover:text-blue-800 underline"
          >
            {{ tag }}
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600 mb-4">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Active Campaigns</h3>
          <p class="text-gray-600">Discover and support causes that matter to our community.</p>
          <router-link to="/campaigns" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            View Campaigns
          </router-link>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600 mb-4">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Make a Donation</h3>
          <p class="text-gray-600">Contribute to initiatives that align with your values.</p>
          <router-link to="/campaigns" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Donate Now
          </router-link>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600 mb-4">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Track Impact</h3>
          <p class="text-gray-600">See the real-world impact of our collective contributions.</p>
          <router-link to="/admin" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
            View Dashboard
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const searchQuery = ref('');
const popularTags = ['environment', 'education', 'health', 'poverty'];

const goToCampaigns = () => {
  if (searchQuery.value.trim()) {
    // Navigate to campaigns with search query
    router.push({
      path: '/campaigns',
      query: { search: searchQuery.value }
    });
  } else {
    router.push('/campaigns');
  }
};

const searchByTag = (tag) => {
  searchQuery.value = tag;
  goToCampaigns();
};
</script> 