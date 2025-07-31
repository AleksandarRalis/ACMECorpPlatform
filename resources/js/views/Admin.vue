<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Side Menu -->
    <div class="w-64 bg-white shadow-lg">
      <div class="p-6 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
      </div>
      
      <nav class="mt-6">
        <div class="px-4 space-y-2">
          <button 
            @click="activeSection = 'dashboard'"
            :class="[
              'w-full text-left px-4 py-2 rounded-lg transition-colors',
              activeSection === 'dashboard' 
                ? 'bg-blue-100 text-blue-700' 
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
              </svg>
              Dashboard
            </div>
          </button>
          
          <button 
            @click="activeSection = 'campaigns'"
            :class="[
              'w-full text-left px-4 py-2 rounded-lg transition-colors',
              activeSection === 'campaigns' 
                ? 'bg-blue-100 text-blue-700' 
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              Campaigns
            </div>
          </button>
          
          <button 
            @click="activeSection = 'users'"
            :class="[
              'w-full text-left px-4 py-2 rounded-lg transition-colors',
              activeSection === 'users' 
                ? 'bg-blue-100 text-blue-700' 
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
              Users
            </div>
          </button>
          
          <button 
            @click="activeSection = 'donations'"
            :class="[
              'w-full text-left px-4 py-2 rounded-lg transition-colors',
              activeSection === 'donations' 
                ? 'bg-blue-100 text-blue-700' 
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
              Donations
            </div>
          </button>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
      <div class="p-2">
        <!-- Dashboard Section -->
        <div v-if="activeSection === 'dashboard'">
          <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-2">Welcome to the admin panel</p>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
          </div>

          <!-- Error State -->
          <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-700">{{ error }}</p>
          </div>

          <!-- Dashboard Content -->
          <div v-else>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
              <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                  <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Campaigns</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.activeCampaigns }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                  <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Raised</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ (stats.totalRaised).toLocaleString() }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                  <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Donors</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.totalDonors }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                  <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Donation</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ stats.avgDonation }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Monthly Statistics Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
              <!-- Monthly Donations Chart -->
              <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900">Monthly Donations Trend</h3>
                </div>
                <div class="p-6">
                  <div class="h-64 flex items-end justify-between space-x-2">
                    <div 
                      v-for="(amount, index) in monthlyStats.donations" 
                      :key="index"
                      class="flex-1 bg-blue-500 rounded-t"
                      :style="{ height: getBarHeight(amount) + '%' }"
                      :title="`$${amount.toLocaleString()} in ${monthlyStats.months[index]}`"
                    ></div>
                  </div>
                  <div class="flex justify-between text-sm text-gray-500 mt-2">
                    <span v-for="month in monthlyStats.months" :key="month">{{ month }}</span>
                  </div>
                </div>
              </div>

              <!-- Monthly Campaigns Chart -->
              <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900">Monthly Campaigns Created</h3>
                </div>
                <div class="p-6">
                  <div class="h-64 flex items-end justify-between space-x-2">
                    <div 
                      v-for="(count, index) in monthlyStats.campaigns" 
                      :key="index"
                      class="flex-1 bg-green-500 rounded-t"
                      :style="{ height: getCampaignBarHeight(count) + '%' }"
                      :title="`${count} campaigns in ${monthlyStats.months[index]}`"
                    ></div>
                  </div>
                  <div class="flex justify-between text-sm text-gray-500 mt-2">
                    <span v-for="month in monthlyStats.months" :key="month">{{ month }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
              <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900">Recent Campaigns</h3>
                </div>
                <div class="p-6">
                  <div class="space-y-4">
                    <div v-for="campaign in recentCampaigns" :key="campaign.id" class="flex items-center justify-between">
                      <div>
                        <p class="font-medium text-gray-900">{{ campaign.title }}</p>
                        <p class="text-sm text-gray-500">{{ campaign.created_at }}</p>
                      </div>
                      <div class="text-right">
                        <p class="font-semibold text-green-600">${{ (campaign.raised).toLocaleString() }}</p>
                        <p class="text-sm text-gray-500">{{ campaign.progress }}%</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900">Recent Donations</h3>
                </div>
                <div class="p-6">
                  <div class="space-y-4">
                    <div v-for="donation in recentDonations" :key="donation.id" class="flex items-center justify-between">
                      <div>
                        <p class="font-medium text-gray-900">{{ donation.name }}</p>
                        <p class="text-sm text-gray-500">{{ donation.campaign }}</p>
                      </div>
                      <div class="text-right">
                        <p class="font-semibold text-green-600">${{ donation.amount }}</p>
                        <p class="text-sm text-gray-500">{{ donation.date }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Campaign Performance Table -->
            <div class="bg-white rounded-lg shadow-md">
              <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Campaign Performance</h3>
              </div>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Goal</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raised</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donors</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Donation</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="campaign in campaignPerformance" :key="campaign.id">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ campaign.title }}</div>
                        <div class="text-sm text-gray-500">{{ campaign.days_left }} days left</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${{ (campaign.goal_amount).toLocaleString() }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${{ (campaign.total_raised).toLocaleString() }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                            <div 
                              class="bg-green-600 h-2 rounded-full" 
                              :style="{ width: Math.min(campaign.progress, 100) + '%' }"
                            ></div>
                          </div>
                          <span class="text-sm text-gray-900">{{ campaign.progress }}%</span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ campaign.donor_count }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${{ campaign.avg_donation }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          :class="[
                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                            campaign.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                          ]"
                        >
                          {{ campaign.status }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

                <!-- Campaigns Section -->
        <CampaignsManagement 
          v-if="activeSection === 'campaigns'"
          :campaigns="allCampaigns"
          @campaigns-updated="fetchAllCampaigns"
        />

        <!-- Users Section -->
        <UsersManagement 
          v-if="activeSection === 'users'"
        />

        <!-- Donations Section -->
        <DonationsManagement 
          v-if="activeSection === 'donations'"
        />
      </div>
    </div>


  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import CampaignsManagement from './CampaignsManagement.vue';
import UsersManagement from './UsersManagement.vue';
import DonationsManagement from './DonationsManagement.vue';

// Reactive data
const activeSection = ref('dashboard');
const loading = ref(false);
const error = ref('');

// Dashboard data
const stats = ref({
  activeCampaigns: 0,
  totalRaised: 0,
  totalDonors: 0,
  avgDonation: 0
});
const recentCampaigns = ref([]);
const recentDonations = ref([]);
const monthlyStats = ref({
  months: [],
  donations: [],
  campaigns: []
});
const campaignPerformance = ref([]);

// Campaigns data
const allCampaigns = ref([]);

// Fetch dashboard data
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/admin/dashboard');
    
    stats.value = response.data.stats;
    recentCampaigns.value = response.data.recentCampaigns;
    recentDonations.value = response.data.recentDonations;
    monthlyStats.value = response.data.monthlyStats;
    campaignPerformance.value = response.data.campaignPerformance;
    
  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = err.response?.data?.message || 'Failed to load dashboard data';
  } finally {
    loading.value = false;
  }
};

// Fetch all campaigns
const fetchAllCampaigns = async () => {
  try {
    const response = await axios.get('/api/admin/campaigns/all');
    allCampaigns.value = response.data.data || response.data;
  } catch (err) {
    console.error('Error fetching campaigns:', err);
  }
};



// Chart helper functions
const getBarHeight = (amount) => {
  if (!monthlyStats.value.donations || monthlyStats.value.donations.length === 0) return 0;
  const maxAmount = Math.max(...monthlyStats.value.donations);
  return maxAmount > 0 ? (amount / maxAmount) * 100 : 0;
};

const getCampaignBarHeight = (count) => {
  if (!monthlyStats.value.campaigns || monthlyStats.value.campaigns.length === 0) return 0;
  const maxCount = Math.max(...monthlyStats.value.campaigns);
  return maxCount > 0 ? (count / maxCount) * 100 : 0;
};



// Watch for section changes
watch(activeSection, (newSection) => {
  if (newSection === 'campaigns') {
    fetchAllCampaigns();
  } else if (newSection === 'dashboard') {
    fetchDashboardData();
  }
});

// Initialize
onMounted(() => {
  fetchDashboardData();
});
</script> 