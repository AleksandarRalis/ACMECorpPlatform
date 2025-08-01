<template>
  <div class="px-4 py-6 sm:px-0">
    <div class="max-w-2xl mx-auto">
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Campaign</h1>
        <p class="text-gray-600 mt-2">Start a new fundraising campaign for a cause you believe in.</p>
      </div>

      <form @submit.prevent="createCampaign" class="bg-white shadow-md rounded-lg p-6">
        <!-- API Error Display -->
        <div v-if="apiError" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Error</h3>
              <div class="mt-2 text-sm text-red-700">
                <p>{{ apiError }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="space-y-6">
          <!-- Campaign Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              Campaign Title *
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.title ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter campaign title"
            />
            <p v-if="fieldErrors.title" class="mt-1 text-sm text-red-600">{{ fieldErrors.title }}</p>
          </div>

          <!-- Campaign Description -->
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
              Description *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.description ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Describe your campaign and its goals"
            ></textarea>
            <p v-if="fieldErrors.description" class="mt-1 text-sm text-red-600">{{ fieldErrors.description }}</p>
          </div>

          <!-- Campaign Category -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
              Category *
            </label>
            <select
              id="category"
              v-model="form.category"
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.category ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
            >
              <option value="">Select a category</option>
              <option v-for="category in categories" :key="category.value" :value="category.value">
                {{ category.label }}
              </option>
            </select>
            <p v-if="fieldErrors.category" class="mt-1 text-sm text-red-600">{{ fieldErrors.category }}</p>
          </div>

          <!-- Funding Goal -->
          <div>
            <label for="goal" class="block text-sm font-medium text-gray-700 mb-2">
              Funding Goal ($) *
            </label>
            <input
              id="goal"
              v-model.number="form.goal"
              type="number"
              min="1"
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.goal ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
              placeholder="Enter funding goal"
            />
            <p v-if="fieldErrors.goal" class="mt-1 text-sm text-red-600">{{ fieldErrors.goal }}</p>
          </div>

          <!-- Start Date -->
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
              Start Date *
            </label>
            <input
              id="start_date"
              v-model="form.start_date"
              type="date"
              :min="minDate"
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.start_date ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
            />
            <p v-if="fieldErrors.start_date" class="mt-1 text-sm text-red-600">{{ fieldErrors.start_date }}</p>
          </div>

          <!-- End Date -->
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
              End Date *
            </label>
            <input
              id="end_date"
              v-model="form.end_date"
              type="date"
              :min="form.start_date || minDate"
              :class="[
                'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                fieldErrors.end_date ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
              ]"
            />
            <p v-if="fieldErrors.end_date" class="mt-1 text-sm text-red-600">{{ fieldErrors.end_date }}</p>
          </div>

          <!-- Campaign Image URL -->
          <div>
            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
              Campaign Image URL
            </label>
            <input
              id="image_url"
              v-model="form.image_url"
              type="url"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="https://example.com/image.jpg"
            />
          </div>

          <!-- Submit Buttons -->
          <div class="flex justify-end space-x-4 pt-6">
            <router-link
              to="/campaigns"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Cancel
            </router-link>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ loading ? 'Creating...' : 'Create Campaign' }}
            </button>
          </div>
        </div>
      </form>
    </div>



    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Campaign Created Successfully!</h3>
            <button @click="closeSuccessModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">Your campaign has been created successfully!</h3>
                <div class="mt-2 text-sm text-green-700">
                  <p>You will be redirected to your campaigns page in a few seconds.</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button 
              @click="closeSuccessModal"
              class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm font-medium hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Stay Here
            </button>
            <button 
              @click="goToMyCampaigns"
              class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              Go to My Campaigns
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useCampaignForm } from '../composables/useCampaignForm';

const router = useRouter();

const {
  form,
  loading,
  minDate,
  categories,
  resetForm,
  getPayload,
  validateForm
} = useCampaignForm();

// Success modal state
const showSuccessModal = ref(false);
const fieldErrors = ref({});
const apiError = ref('');

const createCampaign = async () => {
  // Clear previous errors
  fieldErrors.value = {};
  apiError.value = '';
  
  // Validate form
  const errors = validateForm();
  
  if (Object.keys(errors).length > 0) {
    fieldErrors.value = errors;
    return;
  }

  loading.value = true;
  
  try {
    const payload = getPayload();
    await axios.post('/api/campaigns', payload);
    
    // Show success modal
    showSuccessModal.value = true;
    
    // Auto-redirect after 3 seconds
    setTimeout(() => {
      if (showSuccessModal.value) {
        goToMyCampaigns();
      }
    }, 3000);
  } catch (error) {
    console.error('Error creating campaign:', error);
    apiError.value = error.response?.data?.message || 'Error creating campaign. Please try again.';
  } finally {
    loading.value = false;
  }
};

const closeSuccessModal = () => {
  showSuccessModal.value = false;
  resetForm();
};

const goToMyCampaigns = () => {
  showSuccessModal.value = false;
  resetForm();
  router.push('/campaigns/my');
};

// Keyboard event handlers
const handleKeydown = (event) => {
  if (event.key === 'Escape' && showSuccessModal.value) {
    closeSuccessModal();
  }
};

// Watch for form changes to clear field errors
watch(() => form.title, () => {
  if (fieldErrors.value.title) {
    delete fieldErrors.value.title;
  }
  if (apiError.value) {
    apiError.value = '';
  }
});

watch(() => form.description, () => {
  if (fieldErrors.value.description) {
    delete fieldErrors.value.description;
  }
});

watch(() => form.category, () => {
  if (fieldErrors.value.category) {
    delete fieldErrors.value.category;
  }
});

watch(() => form.goal, () => {
  if (fieldErrors.value.goal) {
    delete fieldErrors.value.goal;
  }
});

watch(() => form.start_date, () => {
  if (fieldErrors.value.start_date) {
    delete fieldErrors.value.start_date;
  }
});

watch(() => form.end_date, () => {
  if (fieldErrors.value.end_date) {
    delete fieldErrors.value.end_date;
  }
});

onMounted(() => {
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
});
</script> 