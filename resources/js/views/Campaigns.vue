<template>
  <div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Fundraising Campaigns</h1>
      <router-link to="/campaigns/create" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
        Create Campaign
      </router-link>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search Input -->
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
            Search Campaigns
          </label>
          <div class="relative">
            <input
              id="search"
              v-model="searchQuery"
              type="text"
              placeholder="Search by title, description..."
              class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Category Filter -->
        <div>
          <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
            Category
          </label>
          <select
            id="category"
            v-model="selectedCategory"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">All Categories</option>
            <option value="environment">Environment</option>
            <option value="education">Education</option>
            <option value="health">Health</option>
            <option value="poverty">Poverty Relief</option>
            <option value="animals">Animal Welfare</option>
            <option value="community">Community Development</option>
            <option value="other">Other</option>
          </select>
        </div>

        <!-- Sort Options -->
        <div>
          <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
            Sort By
          </label>
          <select
            id="sort"
            v-model="sortBy"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
            <option value="goal-high">Goal: High to Low</option>
            <option value="goal-low">Goal: Low to High</option>
            <option value="progress">Progress: High to Low</option>
            <option value="deadline">Deadline: Soonest</option>
          </select>
        </div>
      </div>

      <!-- Search Results Info -->
      <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
        <span>
          Showing {{ filteredCampaigns.length }} of {{ campaigns.length }} campaigns
        </span>
        <button
          v-if="searchQuery || selectedCategory || sortBy !== 'newest'"
          @click="clearFilters"
          class="text-blue-600 hover:text-blue-800 font-medium"
        >
          Clear Filters
        </button>
      </div>
    </div>

    <!-- Campaigns Grid -->
    <div v-if="filteredCampaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="campaign in filteredCampaigns" :key="campaign.id" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-lg font-semibold text-gray-900">{{ campaign.title }}</h3>
            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
              {{ campaign.category }}
            </span>
          </div>
          <p class="text-gray-600 text-sm mb-4">{{ campaign.description }}</p>
          
          <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-500 mb-1">
              <span>Progress</span>
              <span>${{ campaign.raised.toLocaleString() }}/${{ campaign.goal.toLocaleString() }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-green-600 h-2 rounded-full" :style="{ width: progressPercentage(campaign) + '%' }"></div>
            </div>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">{{ campaign.daysLeft }} days left</span>
            <router-link :to="`/campaigns/${campaign.id}`" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
              View Details
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- No Results Message -->
    <div v-else class="text-center py-12">
      <div class="text-gray-500">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No campaigns found</h3>
        <p class="text-gray-600">Try adjusting your search criteria or filters.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const searchQuery = ref('');
const selectedCategory = ref('');
const sortBy = ref('newest');

// Handle search query from URL
onMounted(() => {
  if (route.query.search) {
    searchQuery.value = route.query.search;
  }
});

const campaigns = ref([
  {
    id: 1,
    title: 'Local Food Bank Support',
    description: 'Help provide meals for families in need in our local community.',
    category: 'poverty',
    goal: 10000,
    raised: 6500,
    daysLeft: 15,
    createdAt: '2024-01-15'
  },
  {
    id: 2,
    title: 'Environmental Cleanup Initiative',
    description: 'Support local environmental conservation and cleanup efforts.',
    category: 'environment',
    goal: 5000,
    raised: 3200,
    daysLeft: 8,
    createdAt: '2024-01-10'
  },
  {
    id: 3,
    title: 'Education for All',
    description: 'Provide educational resources and supplies for underprivileged children.',
    category: 'education',
    goal: 15000,
    raised: 12000,
    daysLeft: 22,
    createdAt: '2024-01-20'
  },
  {
    id: 4,
    title: 'Animal Shelter Support',
    description: 'Help provide care and shelter for abandoned animals in our community.',
    category: 'animals',
    goal: 8000,
    raised: 4500,
    daysLeft: 12,
    createdAt: '2024-01-18'
  },
  {
    id: 5,
    title: 'Community Health Clinic',
    description: 'Support free health services for low-income families.',
    category: 'health',
    goal: 20000,
    raised: 18000,
    daysLeft: 5,
    createdAt: '2024-01-05'
  }
]);

const filteredCampaigns = computed(() => {
  let filtered = campaigns.value;

  // Search by title and description
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(campaign => 
      campaign.title.toLowerCase().includes(query) ||
      campaign.description.toLowerCase().includes(query)
    );
  }

  // Filter by category
  if (selectedCategory.value) {
    filtered = filtered.filter(campaign => campaign.category === selectedCategory.value);
  }

  // Sort campaigns
  filtered = [...filtered].sort((a, b) => {
    switch (sortBy.value) {
      case 'newest':
        return new Date(b.createdAt) - new Date(a.createdAt);
      case 'oldest':
        return new Date(a.createdAt) - new Date(b.createdAt);
      case 'goal-high':
        return b.goal - a.goal;
      case 'goal-low':
        return a.goal - b.goal;
      case 'progress':
        return (b.raised / b.goal) - (a.raised / a.goal);
      case 'deadline':
        return a.daysLeft - b.daysLeft;
      default:
        return 0;
    }
  });

  return filtered;
});

const progressPercentage = (campaign) => {
  return Math.min((campaign.raised / campaign.goal) * 100, 100);
};

const clearFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = '';
  sortBy.value = 'newest';
};
</script> 