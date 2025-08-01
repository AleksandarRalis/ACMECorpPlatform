<template>
  <div>
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Donations Management</h1>
      <p class="text-gray-600 mt-2">Manage and view all donations across campaigns</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
      <p class="text-red-700">{{ error }}</p>
    </div>

    <!-- Donations Content -->
    <div v-else>


      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Donations</p>
              <p class="text-2xl font-semibold text-gray-900">{{ donationStats.total }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Amount</p>
              <p class="text-2xl font-semibold text-gray-900">${{ donationStats.totalAmount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Average Donation</p>
              <p class="text-2xl font-semibold text-gray-900">${{ donationStats.averageAmount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input 
              v-model="filters.search" 
              type="text" 
              placeholder="Search by donor name or campaign..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              @input="debounceSearch"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Campaign</label>
            <select 
              v-model="filters.campaign" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              @change="applyFilters"
            >
              <option value="">All Campaigns</option>
              <option v-for="campaign in campaigns" :key="campaign.id" :value="campaign.title">
                {{ campaign.title }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
            <select 
              v-model="filters.month" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              @change="applyFilters"
            >
              <option value="">All Months</option>
              <option v-for="month in availableMonths" :key="month.value" :value="month.value">
                {{ month.label }}
              </option>
            </select>
          </div>

                      <div class="flex items-end space-x-2">
              <button 
                @click="clearFilters"
                class="w-1/2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors"
              >
                Clear Filters
              </button>
              <button 
                @click="applyFilters"
                class="w-1/2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors"
              >
                Apply Filters
              </button>
            </div>
        </div>
      </div>

      <!-- Donations Table -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">All Donations</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
                              <thead class="bg-gray-50">
                    <tr>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="donations.length === 0" class="text-center">
                <td colspan="7" class="px-4 py-6 text-gray-500">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-lg font-medium">No donations found</p>
                    <p class="text-sm">Try adjusting your filters or search terms</p>
                  </div>
                </td>
              </tr>
              <tr v-for="donation in donations" :key="donation.id" class="hover:bg-gray-50">
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ donation.createdBy.name }}</div>
                  <div class="text-sm text-gray-500">{{ donation.createdBy?.email }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ donation.campaign?.title }}</div>
                  <div class="text-sm text-gray-500">{{ donation.campaign?.category }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="text-sm font-semibold text-green-600">${{ (donation.amount).toLocaleString() }}</div>
                </td>
                <td class="px-4 py-3">
                  <div class="text-sm text-gray-900 max-w-xs truncate">
                    {{ donation.message || 'No message' }}
                  </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(donation.created_at) }}</div>
                  <div class="text-sm text-gray-500">{{ formatTime(donation.created_at) }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span 
                    :class="[
                      'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                      donation.details?.payment_status === 'completed' ? 'bg-green-100 text-green-800' : 
                      donation.details?.payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                    ]"
                  >
                    {{ donation.details?.payment_status || 'pending' }}
                  </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                  <button 
                    @click="viewDonation(donation)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination" class="px-4 py-3 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} donations
              <span class="text-xs text-gray-500 ml-2">(Page {{ pagination.current_page }} of {{ pagination.last_page }})</span>
            </div>
            <div class="flex items-center space-x-2">
              <button 
                @click="prevPage"
                :disabled="pagination.current_page === 1"
                :class="[
                  'px-3 py-2 text-sm border rounded-md',
                  pagination.current_page > 1
                    ? 'text-gray-700 border-gray-300 hover:bg-gray-50' 
                    : 'text-gray-400 border-gray-200 cursor-not-allowed'
                ]"
              >
                Previous
              </button>
              
              <!-- Page numbers -->
              <div class="flex space-x-1">
                <button 
                  v-for="page in Array.from({length: pagination.last_page}, (_, i) => i + 1)" 
                  :key="page"
                  @click="goToPage(page)"
                  :class="[
                    'px-3 py-2 text-sm border rounded-md',
                    page === pagination.current_page
                      ? 'bg-blue-600 text-white border-blue-600'
                      : 'text-gray-700 border-gray-300 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
              </div>
              
              <button 
                @click="nextPage"
                :disabled="pagination.current_page === pagination.last_page"
                :class="[
                  'px-3 py-2 text-sm border rounded-md',
                  pagination.current_page < pagination.last_page
                    ? 'text-gray-700 border-gray-300 hover:bg-gray-50' 
                    : 'text-gray-400 border-gray-200 cursor-not-allowed'
                ]"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Donation Detail Modal -->
    <div v-if="selectedDonation" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Donation Details</h3>
            <button @click="selectedDonation = null" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Donor Name</label>
                <p class="text-sm text-gray-900">{{ selectedDonation.createdBy.name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Amount</label>
                <p class="text-sm font-semibold text-green-600">${{ (selectedDonation.amount).toLocaleString() }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Campaign</label>
                <p class="text-sm text-gray-900">{{ selectedDonation.campaign?.title }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <p class="text-sm text-gray-900">{{ formatDate(selectedDonation.created_at) }}</p>
              </div>
            </div>
            
            <div v-if="selectedDonation.message">
              <label class="block text-sm font-medium text-gray-700">Message</label>
              <p class="text-sm text-gray-900">{{ selectedDonation.message }}</p>
            </div>
            
            <div v-if="selectedDonation.details">
              <label class="block text-sm font-medium text-gray-700">Payment Details</label>
              <div class="bg-gray-50 p-3 rounded-md">
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div>
                    <span class="font-medium">Status:</span> 
                    <span :class="[
                      selectedDonation.details.payment_status === 'completed' ? 'text-green-600' : 
                      selectedDonation.details.payment_status === 'failed' ? 'text-red-600' : 'text-yellow-600'
                    ]">{{ selectedDonation.details.payment_status }}</span>
                  </div>
                  <div>
                    <span class="font-medium">Method:</span> {{ selectedDonation.details.payment_method }}
                  </div>
                  <div>
                    <span class="font-medium">Transaction ID:</span> {{ selectedDonation.details.transaction_id }}
                  </div>
                  <div>
                    <span class="font-medium">Processed:</span> {{ formatDate(selectedDonation.details.processed_at) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Reactive data
const loading = ref(false);
const error = ref('');
const donations = ref([]);
const campaigns = ref([]);
const selectedDonation = ref(null);
const pagination = ref(null);
const donationStats = ref({ total: 0, totalAmount: '0.00', averageAmount: '0.00' });
let searchTimeout = null;

// Filters
const filters = ref({
  search: '',
  campaign: '',
  month: ''
});

// Static list of all 12 months
const availableMonths = [
  { value: '1', label: 'January' },
  { value: '2', label: 'February' },
  { value: '3', label: 'March' },
  { value: '4', label: 'April' },
  { value: '5', label: 'May' },
  { value: '6', label: 'June' },
  { value: '7', label: 'July' },
  { value: '8', label: 'August' },
  { value: '9', label: 'September' },
  { value: '10', label: 'October' },
  { value: '11', label: 'November' },
  { value: '12', label: 'December' }
];



// Fetch donations
const fetchDonations = async (page = 1) => {
  try {
    loading.value = true;
    const params = {
      page,
      ...filters.value
    };
    
    const response = await axios.get('/api/donations', { params });
    
    if (response.data.data) {
      // Paginated response
      donations.value = response.data.data;
      pagination.value = response.data.meta;
    } else {
      // Direct array response
      donations.value = response.data;
      pagination.value = null;
    }
    
  } catch (err) {
    console.error('Error fetching donations:', err);
    error.value = err.response?.data?.message || 'Failed to load donations';
  } finally {
    loading.value = false;
  }
};

// Fetch donation statistics
const fetchDonationStats = async () => {
  try {
    const response = await axios.get('/api/donations/stats');
    donationStats.value = response.data;
  } catch (err) {
    console.error('Error fetching donation stats:', err);
    donationStats.value = { total: 0, totalAmount: '0.00', averageAmount: '0.00' };
  }
};

// Fetch campaigns for filter
const fetchCampaigns = async () => {
  try {
    const response = await axios.get('/api/campaigns');
    campaigns.value = response.data.data;
  } catch (err) {
    console.error('Error fetching campaigns:', err);
  }
};

// Debounced search function
const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchDonations(1);
  }, 500);
};

// Apply filters
const applyFilters = () => {
  fetchDonations(1);
};

// Clear filters
const clearFilters = () => {
  filters.value = {
    search: '',
    campaign: '',
    month: ''
  };
  fetchDonations(1);
};

// Pagination functions
const nextPage = () => {
  if (pagination.value && pagination.value.current_page < pagination.value.last_page) {
    fetchDonations(pagination.value.current_page + 1);
  }
};

const prevPage = () => {
  if (pagination.value && pagination.value.current_page > 1) {
    fetchDonations(pagination.value.current_page - 1);
  }
};

const goToPage = (page) => {
  if (pagination.value && page >= 1 && page <= pagination.value.last_page) {
    fetchDonations(page);
  }
};



// View donation details
const viewDonation = async (donation) => {
  try {
    const response = await axios.get(`/api/donations/${donation.id}`);
    selectedDonation.value = response.data;
  } catch (err) {
    console.error('Error fetching donation details:', err);
  }
};

// Format date
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString();
};

// Format time
const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString();
};



// Initialize
onMounted(() => {
  fetchDonations();
  fetchCampaigns();
  fetchDonationStats();
});
</script> 