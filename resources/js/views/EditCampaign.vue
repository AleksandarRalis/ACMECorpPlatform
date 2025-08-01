<template>
  <div class="px-4 py-6 sm:px-0">
    <div class="max-w-2xl mx-auto">
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Campaign</h1>
        <p class="text-gray-600 mt-2">Update your campaign details and settings.</p>
      </div>

      <!-- Loading State -->
      <div v-if="initialLoading" class="text-center py-12">
        <div class="text-gray-500">
          <svg class="animate-spin mx-auto h-8 w-8 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-600">Loading campaign data...</p>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error loading campaign</h3>
            <div class="mt-2 text-sm text-red-700">
              <p>{{ error }}</p>
            </div>
            <div class="mt-4">
              <button
                @click="fetchCampaign"
                class="bg-red-100 text-red-800 px-3 py-2 rounded-md text-sm font-medium hover:bg-red-200"
              >
                Try Again
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Form -->
      <form v-else @submit.prevent="updateCampaign" class="bg-white shadow-md rounded-lg p-6">
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
              to="/campaigns/my"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Cancel
            </router-link>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ loading ? 'Updating...' : 'Update Campaign' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { useCampaignForm } from '../composables/useCampaignForm';

const router = useRouter();
const route = useRoute();

const {
  form,
  loading,
  minDate,
  categories,
  populateForm,
  getPayload,
  validateForm
} = useCampaignForm();

const initialLoading = ref(true);
const error = ref('');
const fieldErrors = ref({});
const apiError = ref('');

onMounted(async () => {
  await fetchCampaign();
});

const fetchCampaign = async () => {
  initialLoading.value = true;
  error.value = '';
  
  try {
    const response = await axios.get(`/api/campaigns/${route.params.id}`);
    const campaign = response.data.data || response.data;
    populateForm(campaign);
  } catch (err) {
    console.error('Error fetching campaign:', err);
    error.value = err.response?.data?.message || 'Failed to load campaign data. Please try again.';
  } finally {
    initialLoading.value = false;
  }
};

const updateCampaign = async () => {
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
    await axios.put(`/api/campaigns/${route.params.id}`, payload);
    
    // Redirect back to My Campaigns page
    router.push('/campaigns/my');
  } catch (err) {
    console.error('Error updating campaign:', err);
    apiError.value = err.response?.data?.message || 'Error updating campaign. Please try again.';
  } finally {
    loading.value = false;
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
</script> 