<template>
  <div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">My Campaigns</h1>
      <router-link to="/campaigns/create" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
        Create New Campaign
      </router-link>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search Input -->
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
            Search My Campaigns
          </label>
          <div class="relative">
            <input
              id="search"
              v-model="searchQuery"
              type="text"
              placeholder="Search by title, description..."
              class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              @input="debounceSearch"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Status Filter -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
            Status
          </label>
          <select
            id="status"
            v-model="selectedStatus"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @change="fetchMyCampaigns"
          >
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
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
            @change="applySorting"
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
          Showing {{ campaigns.length }} campaigns
        </span>
        <button
          v-if="searchQuery || selectedStatus || sortBy !== 'newest'"
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
        <p class="text-gray-600">Loading your campaigns...</p>
      </div>
    </div>

    <!-- Campaigns Grid -->
    <div v-else-if="campaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="campaign in campaigns" :key="campaign.id" class="bg-white rounded-lg shadow-md overflow-hidden">
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
            <div class="flex flex-col items-end space-y-1">
              <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                {{ campaign.category }}
              </span>
              <span :class="getStatusClass(campaign.status)" class="text-xs px-2 py-1 rounded-full">
                {{ campaign.status }}
              </span>
            </div>
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

          <div class="flex justify-between items-center mb-4">
            <span class="text-sm text-gray-500">
              {{ campaign.days_left }} days left
            </span>
            <div class="text-sm text-gray-500">
              {{ campaign.donations_count }} donations
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex space-x-2">
            <router-link :to="`/campaigns/${campaign.id}`" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium text-center">
              View Details
            </router-link>
            <button 
              v-if="campaign.status === 'pending'"
              @click="editCampaign(campaign.id)"
              class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium"
            >
              Edit
            </button>
            <button 
              v-if="campaign.donations_count === 0"
              @click="deleteCampaign(campaign)"
              class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- No Results Message -->
    <div v-else class="text-center py-12">
      <div class="text-gray-500">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No campaigns found</h3>
        <p class="text-gray-600 mb-4">You haven't created any campaigns yet, or try adjusting your search criteria.</p>
        <router-link to="/campaigns/create" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
          Create Your First Campaign
        </router-link>
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
          <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Campaign</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Are you sure you want to delete "<strong>{{ campaignToDelete?.title }}</strong>"? This action cannot be undone.
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
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const searchQuery = ref('');
const selectedStatus = ref('');
const sortBy = ref('newest');
const campaigns = ref([]);
const loading = ref(false);
const showDeleteModal = ref(false);
const deleteLoading = ref(false);
const campaignToDelete = ref(null);
let searchTimeout = null;

onMounted(async () => {
  await fetchMyCampaigns();
});

const fetchMyCampaigns = async () => {
  loading.value = true;
  try {
    const params = {};
    
    if (searchQuery.value) {
      params.search = searchQuery.value;
    }
    
    if (selectedStatus.value) {
      params.status = selectedStatus.value;
    }
    
    const response = await axios.get('/api/campaigns/my', { params });
    campaigns.value = response.data.data || response.data;
    
    // Apply sorting on the frontend
    applySorting();
  } catch (error) {
    console.error('Error fetching my campaigns:', error);
    campaigns.value = [];
  } finally {
    loading.value = false;
  }
};

const applySorting = () => {
  if (!campaigns.value || campaigns.value.length === 0) return;
  
  campaigns.value.sort((a, b) => {
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
        return new Date(b.created_at) - new Date(a.created_at);
    }
  });
};

const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchMyCampaigns();
  }, 500);
};

const getStatusClass = (status) => {
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800',
    'completed': 'bg-blue-100 text-blue-800',
    'cancelled': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
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
  selectedStatus.value = '';
  sortBy.value = 'newest';
  fetchMyCampaigns();
};

// Watch for changes in sortBy and apply sorting
watch(sortBy, () => {
  if (campaigns.value && campaigns.value.length > 0) {
    applySorting();
  }
});

const editCampaign = (campaignId) => {
  // Navigate to the edit campaign form
  router.push(`/campaigns/edit/${campaignId}`);
};

const deleteCampaign = (campaign) => {
  campaignToDelete.value = campaign;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  campaignToDelete.value = null;
};

const confirmDelete = async () => {
  try {
    deleteLoading.value = true;
    await axios.delete(`/api/campaigns/${campaignToDelete.value.id}`);
    
    // Close modal and refresh the list
    closeDeleteModal();
    await fetchMyCampaigns();
  } catch (error) {
    console.error('Error deleting campaign:', error);
    alert('Failed to delete campaign. Please try again.');
  } finally {
    deleteLoading.value = false;
  }
};
</script> 