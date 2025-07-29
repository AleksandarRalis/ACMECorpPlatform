<template>
  <div class="px-4 py-6 sm:px-0">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Active Campaigns</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.activeCampaigns }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Raised</p>
            <p class="text-2xl font-semibold text-gray-900">${{ stats.totalRaised.toLocaleString() }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Donors</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.totalDonors }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Avg. Donation</p>
            <p class="text-2xl font-semibold text-gray-900">${{ stats.avgDonation }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                <p class="font-semibold text-green-600">${{ campaign.raised.toLocaleString() }}</p>
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const stats = ref({
  activeCampaigns: 0,
  totalRaised: 0,
  totalDonors: 0,
  avgDonation: 0
});

const recentCampaigns = ref([]);
const recentDonations = ref([]);

onMounted(() => {
  // Mock data - in real app, fetch from API
  stats.value = {
    activeCampaigns: 8,
    totalRaised: 45600,
    totalDonors: 342,
    avgDonation: 133
  };

  recentCampaigns.value = [
    { id: 1, title: 'Local Food Bank Support', raised: 6500, progress: 65, created_at: '2024-01-15' },
    { id: 2, title: 'Environmental Cleanup', raised: 3200, progress: 64, created_at: '2024-01-10' },
    { id: 3, title: 'Education for All', raised: 12000, progress: 80, created_at: '2024-01-05' }
  ];

  recentDonations.value = [
    { id: 1, name: 'John Smith', amount: 100, campaign: 'Local Food Bank', date: '2024-01-20' },
    { id: 2, name: 'Sarah Johnson', amount: 50, campaign: 'Environmental Cleanup', date: '2024-01-19' },
    { id: 3, name: 'Mike Davis', amount: 200, campaign: 'Education for All', date: '2024-01-18' }
  ];
});
</script> 