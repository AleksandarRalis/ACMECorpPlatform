<template>
  <div class="px-4 py-6 sm:px-0">
    <div v-if="campaign" class="max-w-4xl mx-auto">
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Campaign Hero Image -->
        <div class="h-64 md:h-80 bg-gray-200 overflow-hidden">
          <img 
            v-if="campaign.image_url" 
            :src="campaign.image_url" 
            :alt="campaign.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
            <div class="text-center">
              <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-gray-500 text-sm">No image available</p>
            </div>
          </div>
        </div>
        
        <div class="p-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ campaign.title }}</h1>
          <p class="text-gray-600 text-lg mb-6">{{ campaign.description }}</p>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Campaign Progress</h3>
              <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-500 mb-1">
                  <span>Raised</span>
                  <span>${{ campaign.current_amount.toLocaleString() }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mb-1">
                  <span>Goal</span>
                  <span>${{ campaign.goal_amount.toLocaleString() }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-2">
                  <div class="bg-green-600 h-3 rounded-full" :style="{ width: progressPercentage + '%' }"></div>
                </div>
                <div class="text-center mt-2">
                  <span class="text-sm font-medium text-gray-900">{{ progressPercentage }}% Complete</span>
                </div>
              </div>
              
              <div class="text-sm text-gray-500">
                <p>{{ campaign.days_left }} days remaining</p>
                <!-- <p>{{ campaign.donors }} donors so far</p> -->
              </div>
            </div>
            
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Make a Donation</h3>
              <form @submit.prevent="makeDonation" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Donation Amount</label>
                  <div class="grid grid-cols-3 gap-2 mb-2">
                    <button 
                      v-for="amount in [25, 50, 100]" 
                      :key="amount"
                      type="button"
                      @click="selectedAmount = amount"
                      :class="[
                        'px-3 py-2 text-sm font-medium rounded-md border',
                        selectedAmount === amount 
                          ? 'bg-blue-600 text-white border-blue-600' 
                          : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                      ]"
                    >
                      ${{ amount }}
                    </button>
                  </div>
                  <input 
                    v-model.number="customAmount"
                    type="number" 
                    placeholder="Or enter custom amount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    min="1"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Message (Optional)</label>
                  <textarea 
                    v-model="donationMessage"
                    rows="3"
                    placeholder="Leave a message of support..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  ></textarea>
                </div>
                
                <button 
                  type="submit"
                  class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-md font-medium"
                >
                  Donate Now
                </button>
              </form>
            </div>
          </div>
          
          <!-- <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Donations</h3>
            <div class="space-y-3">
              <div v-for="donation in campaign.recentDonations" :key="donation.id" class="flex justify-between items-center py-2">
                <div>
                  <p class="font-medium text-gray-900">{{ donation.name }}</p>
                  <p class="text-sm text-gray-500">{{ donation.message }}</p>
                </div>
                <span class="font-semibold text-green-600">${{ donation.amount }}</span>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const campaign = ref(null);
const selectedAmount = ref(50);
const customAmount = ref('');
const donationMessage = ref('');

const progressPercentage = computed(() => {
  if (!campaign.value) return 0;
  return campaign.value.progress_percentage || 0;
});

const makeDonation = () => {
  const amount = customAmount.value || selectedAmount.value;
  if (!amount || amount <= 0) {
    alert('Please enter a valid donation amount');
    return;
  }
  
  // TODO: Implement donation logic
  alert(`Thank you for your donation of $${amount}!`);
  
  // Reset form
  selectedAmount.value = 50;
  customAmount.value = '';
  donationMessage.value = '';
};

onMounted(async () => {
  try {
    const response = await axios.get(`/api/campaigns/${route.params.id}`);
    campaign.value = response.data;
  } catch (error) {
    console.error('Error fetching campaign:', error);
  }
});
</script> 