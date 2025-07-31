<template>
  <div>
    <div class="mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Campaign Management</h1>
        <p class="text-gray-600 mt-2">Manage all campaigns</p>
      </div>
    </div>

    <!-- Campaigns Table -->
    <div class="bg-white rounded-lg shadow-md">
      <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">All Campaigns</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Goal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raised</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="campaign in campaigns" :key="campaign.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ campaign.title }}</div>
                <div class="text-sm text-gray-500">{{ campaign.description?.substring(0, 50) }}...</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ (campaign.goal_amount).toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ (campaign.current_amount).toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                    <div 
                      class="bg-green-600 h-2 rounded-full" 
                      :style="{ width: Math.min(campaign.progress_percentage, 100) + '%' }"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-900">{{ campaign.progress_percentage }}%</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                    campaign.status === 'active' ? 'bg-green-100 text-green-800' : 
                    campaign.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                    campaign.status === 'completed' ? 'bg-blue-100 text-blue-800' :
                    campaign.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                    'bg-gray-100 text-gray-800'
                  ]"
                >
                  {{ campaign.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button 
                  v-if="campaign.status === 'pending'"
                  @click="activateCampaign(campaign)"
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  Activate
                </button>
                <button 
                  @click="editCampaign(campaign)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button 
                  @click="deleteCampaign(campaign)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Edit Campaign Modal -->
    <div v-if="showEditCampaignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Edit Campaign</h3>
            <button 
              @click="closeEditModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form @submit.prevent="updateCampaign" class="space-y-4">
            <!-- Title -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Campaign Title</label>
              <input
                id="title"
                v-model="editForm.title"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter campaign title"
              />
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea
                id="description"
                v-model="editForm.description"
                rows="4"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter campaign description"
              ></textarea>
            </div>

            <!-- Category -->
            <div>
              <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select
                id="category"
                v-model="editForm.category"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Select a category</option>
                <option value="education">Education</option>
                <option value="healthcare">Healthcare</option>
                <option value="environment">Environment</option>
                <option value="poverty">Poverty Relief</option>
                <option value="disaster">Disaster Relief</option>
                <option value="animals">Animal Welfare</option>
                <option value="arts">Arts & Culture</option>
                <option value="sports">Sports</option>
                <option value="technology">Technology</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Goal Amount -->
            <div>
              <label for="goal_amount" class="block text-sm font-medium text-gray-700 mb-1">Goal Amount ($)</label>
              <input
                id="goal_amount"
                v-model="editForm.goal_amount"
                type="number"
                min="0"
                step="0.01"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter goal amount"
              />
            </div>

            <!-- Start Date -->
            <div>
              <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
              <input
                id="start_date"
                v-model="editForm.start_date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <!-- End Date -->
            <div>
              <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
              <input
                id="end_date"
                v-model="editForm.end_date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <!-- Image URL -->
            <div>
              <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
              <input
                id="image_url"
                v-model="editForm.image_url"
                type="url"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="https://example.com/image.jpg"
              />
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                id="status"
                v-model="editForm.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <!-- Error Message -->
            <div v-if="editError" class="bg-red-50 border border-red-200 rounded-lg p-3">
              <p class="text-sm text-red-700">{{ editError }}</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="editLoading"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="editLoading">Updating...</span>
                <span v-else>Update Campaign</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-4">{{ successMessage }}</h3>
          <div class="mt-4">
            <button
              @click="closeSuccessModal"
              class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              OK
            </button>
          </div>
        </div>
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

    <!-- Activate Confirmation Modal -->
    <div v-if="showActivateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-4">Activate Campaign</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Are you sure you want to activate "<strong>{{ campaignToActivate?.title }}</strong>"? This will make the campaign visible to users and allow donations.
            </p>
          </div>
          <div class="flex justify-center space-x-3 mt-4">
            <button
              @click="closeActivateModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Cancel
            </button>
            <button
              @click="confirmActivate"
              :disabled="activateLoading"
              class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="activateLoading">Activating...</span>
              <span v-else>Activate</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Props
const props = defineProps({
  campaigns: {
    type: Array,
    default: () => []
  }
});

// Emits
const emit = defineEmits(['campaigns-updated']);

// Reactive data
const showEditCampaignModal = ref(false);
const showSuccessModal = ref(false);
const showDeleteModal = ref(false);
const showActivateModal = ref(false);
const editLoading = ref(false);
const deleteLoading = ref(false);
const activateLoading = ref(false);
const editError = ref('');
const successMessage = ref('');
const campaignToDelete = ref(null);
const campaignToActivate = ref(null);

// Edit form data
const editForm = ref({
  id: null,
  title: '',
  description: '',
  category: '',
  goal_amount: '',
  start_date: '',
  end_date: '',
  image_url: '',
  status: 'active'
});

// Campaign actions
const editCampaign = (campaign) => {
  // Populate the edit form with campaign data
  editForm.value = {
    id: campaign.id,
    title: campaign.title || '',
    description: campaign.description || '',
    category: campaign.category || '',
    goal_amount: campaign.goal_amount || '',
    start_date: campaign.start_date ? new Date(campaign.start_date).toISOString().split('T')[0] : '',
    end_date: campaign.end_date ? new Date(campaign.end_date).toISOString().split('T')[0] : '',
    image_url: campaign.image_url || '',
    status: campaign.status || 'active'
  };
  
  showEditCampaignModal.value = true;
  editError.value = '';
};

const closeEditModal = () => {
  showEditCampaignModal.value = false;
  editForm.value = {
    id: null,
    title: '',
    description: '',
    category: '',
    goal_amount: '',
    start_date: '',
    end_date: '',
    image_url: '',
    status: 'active'
  };
  editError.value = '';
};

const closeSuccessModal = () => {
  showSuccessModal.value = false;
  successMessage.value = '';
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  campaignToDelete.value = null;
};

const updateCampaign = async () => {
  try {
    editLoading.value = true;
    editError.value = '';
    
    const response = await axios.put(`/api/admin/campaigns/${editForm.value.id}`, {
      title: editForm.value.title,
      description: editForm.value.description,
      category: editForm.value.category,
      goal_amount: parseFloat(editForm.value.goal_amount),
      start_date: editForm.value.start_date,
      end_date: editForm.value.end_date,
      image_url: editForm.value.image_url,
      status: editForm.value.status
    });
    
    // Emit event to parent to refresh campaigns
    emit('campaigns-updated');
    
    // Close edit modal and show success message
    closeEditModal();
    successMessage.value = 'Campaign updated successfully!';
    showSuccessModal.value = true;
    
  } catch (err) {
    console.error('Error updating campaign:', err);
    editError.value = err.response?.data?.message || 'Failed to update campaign. Please try again.';
  } finally {
    editLoading.value = false;
  }
};

const activateCampaign = (campaign) => {
  campaignToActivate.value = campaign;
  showActivateModal.value = true;
};

const closeActivateModal = () => {
  showActivateModal.value = false;
  campaignToActivate.value = null;
};

const confirmActivate = async () => {
  try {
    activateLoading.value = true;
    await axios.post(`/api/admin/campaigns/${campaignToActivate.value.id}/activate`);
    
    // Emit event to parent to refresh campaigns
    emit('campaigns-updated');
    
    // Close modal and show success message
    closeActivateModal();
    successMessage.value = 'Campaign activated successfully!';
    showSuccessModal.value = true;
    
  } catch (err) {
    console.error('Error activating campaign:', err);
    closeActivateModal();
    if (err.response?.data?.message) {
      successMessage.value = err.response.data.message;
      showSuccessModal.value = true;
    } else {
      successMessage.value = 'Failed to activate campaign. Please try again.';
      showSuccessModal.value = true;
    }
  } finally {
    activateLoading.value = false;
  }
};

const deleteCampaign = (campaign) => {
  campaignToDelete.value = campaign;
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  try {
    deleteLoading.value = true;
    await axios.delete(`/api/admin/campaigns/${campaignToDelete.value.id}`);
    
    // Emit event to parent to refresh campaigns
    emit('campaigns-updated');
    
    // Close delete modal and show success message
    closeDeleteModal();
    successMessage.value = 'Campaign deleted successfully!';
    showSuccessModal.value = true;
    
  } catch (err) {
    console.error('Error deleting campaign:', err);
    closeDeleteModal();
    if (err.response?.data?.message) {
      successMessage.value = err.response.data.message;
      showSuccessModal.value = true;
    }
  } finally {
    deleteLoading.value = false;
  }
};
</script> 