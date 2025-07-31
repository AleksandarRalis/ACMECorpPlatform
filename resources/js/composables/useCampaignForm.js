import { ref, computed } from 'vue';
import axios from 'axios';

export function useCampaignForm() {
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

  const categories = [
    { value: 'environment', label: 'Environment' },
    { value: 'education', label: 'Education' },
    { value: 'health', label: 'Health' },
    { value: 'poverty', label: 'Poverty Relief' },
    { value: 'animals', label: 'Animal Welfare' },
    { value: 'community', label: 'Community Development' },
    { value: 'other', label: 'Other' }
  ];

  const resetForm = () => {
    form.value = {
      title: '',
      description: '',
      category: '',
      goal: '',
      start_date: '',
      end_date: '',
      image_url: ''
    };
  };

  const populateForm = (campaign) => {
    form.value = {
      title: campaign.title,
      description: campaign.description,
      category: campaign.category,
      goal: campaign.goal_amount,
      start_date: campaign.start_date ? new Date(campaign.start_date).toISOString().split('T')[0] : '',
      end_date: campaign.end_date ? new Date(campaign.end_date).toISOString().split('T')[0] : '',
      image_url: campaign.image_url || ''
    };
  };

  const getPayload = () => {
    return {
      title: form.value.title,
      description: form.value.description,
      category: form.value.category,
      goal_amount: form.value.goal,
      start_date: form.value.start_date,
      end_date: form.value.end_date,
      image_url: form.value.image_url
    };
  };

  const validateForm = () => {
    const errors = [];
    
    if (!form.value.title.trim()) {
      errors.push('Campaign title is required');
    }
    
    if (!form.value.description.trim()) {
      errors.push('Campaign description is required');
    }
    
    if (!form.value.category) {
      errors.push('Please select a category');
    }
    
    if (!form.value.goal || form.value.goal <= 0) {
      errors.push('Please enter a valid funding goal');
    }
    
    if (!form.value.start_date) {
      errors.push('Start date is required');
    }
    
    if (!form.value.end_date) {
      errors.push('End date is required');
    }
    
    if (form.value.start_date && form.value.end_date && form.value.start_date >= form.value.end_date) {
      errors.push('End date must be after start date');
    }
    
    return errors;
  };

  return {
    form,
    loading,
    minDate,
    categories,
    resetForm,
    populateForm,
    getPayload,
    validateForm
  };
} 