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

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="text-gray-500">
        <svg class="animate-spin mx-auto h-8 w-8 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-600">Loading campaigns...</p>
      </div>
    </div>

    <!-- Campaigns Grid -->
    <div v-else-if="filteredCampaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="campaign in filteredCampaigns" :key="campaign.id" class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Campaign Image -->
        <div class="h-48 bg-gray-200 overflow-hidden">
          <img 
            v-if="campaign.image_url" 
            :src="campaign.image_url" 
            :alt="campaign.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
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
              <span>${{ campaign.current_amount.toLocaleString() }}/${{ campaign.goal_amount.toLocaleString() }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-green-600 h-2 rounded-full" :style="{ width: campaign.progress_percentage + '%' }"></div>
            </div>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">
              {{ campaign.days_left }} days left
            </span>
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
import axios from 'axios';
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const searchQuery = ref('');
const selectedCategory = ref('');
const sortBy = ref('newest');
const campaigns = ref([]);
const loading = ref(false);

// Handle search query from URL
onMounted(async () => {
  if (route.query.search) {
    searchQuery.value = route.query.search;
  }
  
  // Fetch campaigns from API
  await fetchCampaigns();
});

const fetchCampaigns = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/campaigns');
    campaigns.value = response.data.data
  } catch (error) {
    console.error('Error fetching campaigns:', error);
    campaigns.value = [];
  } finally {
    loading.value = false;
  }
};

const filteredCampaigns = computed(() => {
  let filtered = campaigns.value || [];

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
        return new Date(b.created_at) - new Date(a.created_at);
      case 'oldest':
        return new Date(a.created_at) - new Date(b.created_at);
      case 'goal-high':
        return b.goal_amount - a.goal_amount;
      case 'goal-low':
        return a.goal_amount - b.goal_amount;
      case 'progress':
        return (b.current_amount / b.goal_amount) - (a.current_amount / a.goal_amount);
      case 'deadline':
        return daysLeft(a) - daysLeft(b);
      default:
        return 0;
    }
  });

  return filtered;
});

const progressPercentage = (campaign) => {
  return Math.min((campaign.current_amount / campaign.goal_amount) * 100, 100);
};

const daysLeft = (campaign) => {
  const today = new Date();
  const endDate = new Date(campaign.end_date);
  const timeDiff = endDate.getTime() - today.getTime();
  const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
  
  if (daysDiff < 0) {
    return 0;
  }
  
  return daysDiff;
};

const clearFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = '';
  sortBy.value = 'newest';
};
</script> 