<template>
  <div class="px-4 py-6 sm:px-0">
    <!-- Thank You Modal -->
    <div v-if="showThankYouModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Thank You for Your Donation!</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500 mb-4">
              Your generous donation of <span class="font-semibold text-green-600">${{ donationAmount }}</span> has been successfully processed.
            </p>
            <div v-if="transactionId" class="bg-gray-50 rounded-lg p-3 mb-4">
              <p class="text-xs text-gray-600">Transaction ID:</p>
              <p class="text-sm font-mono text-gray-800">{{ transactionId }}</p>
            </div>
            <p class="text-sm text-gray-500">
              Your contribution will make a real difference. Thank you for supporting this cause!
            </p>
          </div>
          <div class="items-center px-4 py-3">
            <button
              @click="closeThankYouModal"
              class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300"
            >
              Continue
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Modal -->
    <div v-if="showErrorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Donation Failed</h3>
          <div class="mt-2 px-7 py-3">
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 max-h-32 overflow-y-auto">
              <p class="text-sm text-red-700 break-words">
                {{ errorMessage }}
              </p>
            </div>
            <p class="text-sm text-gray-500">
              Please try again or contact support if the problem persists.
            </p>
          </div>
          <div class="items-center px-4 py-3">
            <button
              @click="closeErrorModal"
              class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
            >
              Try Again
            </button>
          </div>
        </div>
      </div>
    </div>

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
          <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900">{{ campaign.title }}</h1>
            <div class="flex items-center">
              <span 
                :class="[
                  'px-3 py-1 rounded-full text-sm font-medium',
                  campaign.status === 'active' ? 'bg-green-100 text-green-800' : '',
                  campaign.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '',
                  campaign.status === 'completed' ? 'bg-blue-100 text-blue-800' : '',
                  campaign.status === 'cancelled' ? 'bg-red-100 text-red-800' : ''
                ]"
              >
                {{ campaign.status.charAt(0).toUpperCase() + campaign.status.slice(1) }}
              </span>
            </div>
          </div>
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
                  <div class="bg-green-600 h-3 rounded-full" :style="{ width: campaign.progress_percentage + '%' }"></div>
                </div>
                <div class="text-center mt-2">
                  <span class="text-sm font-medium text-gray-900">{{ campaign.progress_percentage }}% Complete</span>
                </div>
              </div>
              
              <div class="text-sm text-gray-500">
                <p>{{ campaign.days_left }} days remaining</p>
              </div>
            </div>
            
            <div v-if="campaign.status === 'active'">
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
                  :disabled="isDonating"
                  :class="[
                    'w-full py-3 px-4 rounded-md font-medium',
                    isDonating 
                      ? 'bg-gray-400 cursor-not-allowed' 
                      : 'bg-green-600 hover:bg-green-700'
                  ]"
                >
                  <span v-if="isDonating" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" 
                      viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" 
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                  </span>
                  <span v-else>Donate Now</span>
                </button>
              </form>
            </div>
            
            <!-- Message for non-active campaigns -->
            <div v-else class="bg-gray-50 rounded-lg p-6 text-center">
              <div class="flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">
                {{ campaign.status === 'pending' ? 'Campaign Pending' : 
                   campaign.status === 'completed' ? 'Campaign Completed' : 
                   campaign.status === 'cancelled' ? 'Campaign Cancelled' : 'Campaign Unavailable' }}
              </h3>
              <p class="text-gray-600">
                {{ campaign.status === 'pending' ? 'This campaign is currently pending approval and donations are not yet accepted.' :
                   campaign.status === 'completed' ? 'This campaign has been completed and is no longer accepting donations.' :
                   campaign.status === 'cancelled' ? 'This campaign has been cancelled and is no longer accepting donations.' :
                   'This campaign is inactive and currently unavailable for donations.' }}
              </p>
            </div>
          </div>
          
          <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Donations</h3>
            <div class="space-y-3">
              <div v-for="donation in campaign.donations" :key="donation.id" class="flex justify-between items-center py-2">
                <div>
                  <p class="font-medium text-gray-900">{{ donation.name }}</p>
                  <p class="text-sm text-gray-500">{{ donation.message }}</p>
                </div>
                <span class="font-semibold text-green-600">${{ donation.amount }}</span>
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
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const campaign = ref(null);
const selectedAmount = ref(50);
const customAmount = ref('');
const donationMessage = ref('');

// Modal state
const showThankYouModal = ref(false);
const showErrorModal = ref(false);
const donationAmount = ref(0);
const transactionId = ref('');
const errorMessage = ref('');



const isDonating = ref(false);

const makeDonation = async () => {
  const amount = customAmount.value || selectedAmount.value;
  if (!amount || amount <= 0) {
    errorMessage.value = 'Please enter a valid donation amount';
    showErrorModal.value = true;
    return;
  }
  
  isDonating.value = true;
  
  try {
    const response = await axios.post(`/api/donations/${route.params.id}`, {
      amount: amount,
      message: donationMessage.value,
    });
    
    // Show success modal
    donationAmount.value = amount;
    transactionId.value = response.data.details.transaction_id || 'N/A';
    showThankYouModal.value = true;
    
    // Reset form
    selectedAmount.value = 50;
    customAmount.value = '';
    donationMessage.value = '';
    
    // Refresh campaign data to update progress
    await fetchCampaign();
    
  } catch (error) {
    console.error('Donation error:', error);
    
    if (error.response?.data?.message) {
      errorMessage.value = `Donation failed: ${error.response.data.message}`;
    } else {
      errorMessage.value = 'Donation failed. Please try again.';
    }
    
    showErrorModal.value = true;
  } finally {
    isDonating.value = false;
  }
};

const fetchCampaign = async () => {
  try {
    const response = await axios.get(`/api/campaigns/${route.params.id}`);
    campaign.value = response.data;
  } catch (error) {
    console.error('Error fetching campaign:', error);
  }
};

const closeThankYouModal = () => {
  showThankYouModal.value = false;
  donationAmount.value = 0;
  transactionId.value = '';
};

const closeErrorModal = () => {
  showErrorModal.value = false;
  errorMessage.value = '';
};

onMounted(async () => {
  await fetchCampaign();
});
</script> 