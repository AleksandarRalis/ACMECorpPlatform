<template>
  <div class="px-4 py-6 sm:px-0">
    <div class="max-w-2xl mx-auto">
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Campaign</h1>
        <p class="text-gray-600 mt-2">Start a new fundraising campaign for a cause you believe in.</p>
      </div>

      <form @submit.prevent="createCampaign" class="bg-white shadow-md rounded-lg p-6">
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
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Enter campaign title"
            />
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
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Describe your campaign and its goals"
            ></textarea>
          </div>

          <!-- Campaign Category -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
              Category *
            </label>
            <select
              id="category"
              v-model="form.category"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Select a category</option>
              <option value="environment">Environment</option>
              <option value="education">Education</option>
              <option value="health">Health</option>
              <option value="poverty">Poverty Relief</option>
              <option value="animals">Animal Welfare</option>
              <option value="community">Community Development</option>
              <option value="other">Other</option>
            </select>
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
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Enter funding goal"
            />
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
              required
              :min="minDate"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
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
              required
              :min="form.start_date || minDate"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
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
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(false);

const form = ref({
  title: '',
  description: '',
  category: '',
  goal: '',
  start_date: '',
  end_date: '',
  image_url: ''
});

const minDate = computed(() => {
  const today = new Date();
  return today.toISOString().split('T')[0];
});

const createCampaign = async () => {
  loading.value = true;
  
  try {
    const payload = {
      title: form.value.title,
      description: form.value.description,
      category: form.value.category,
      goal_amount: form.value.goal,
      start_date: form.value.start_date,
      end_date: form.value.end_date,
      image_url: form.value.image_url
    };

    await axios.post('/api/campaigns', payload);
    
    router.push('/campaigns');
  } catch (error) {
    console.error('Error creating campaign:', error);
    alert('Error creating campaign. Please try again.');
  } finally {
    loading.value = false;
  }
};
</script> 