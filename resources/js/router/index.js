import { createRouter, createWebHistory } from 'vue-router';
import Campaigns from '../views/Campaigns.vue';
import CampaignDetail from '../views/CampaignDetail.vue';
import CreateCampaign from '../views/CreateCampaign.vue';
import EditCampaign from '../views/EditCampaign.vue';
import MyCampaigns from '../views/MyCampaigns.vue';
import Admin from '../views/Admin.vue';
import { useAuthStore } from '../stores/auth';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Campaigns
  },
  {
    path: '/campaigns',
    name: 'Campaigns',
    component: Campaigns
  },
  {
    path: '/campaigns/create',
    name: 'CreateCampaign',
    component: CreateCampaign
  },
  {
    path: '/campaigns/edit/:id',
    name: 'EditCampaign',
    component: EditCampaign,
    props: true
  },
  {
    path: '/campaigns/my',
    name: 'MyCampaigns',
    component: MyCampaigns
  },
  {
    path: '/campaigns/:id',
    name: 'CampaignDetail',
    component: CampaignDetail,
    props: true
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Admin
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue')
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard for authentication and authorization
router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore();
  
  // If not authenticated and not going to login/register, redirect to login
  if (!auth.token && to.name !== 'Login' && to.name !== 'Register') {
    return next({ name: 'Login' });
  }
  
  // If authenticated but user data is missing, fetch it
  if (auth.token && !auth.user) {
    try {
      await auth.fetchUser();
    } catch (error) {
      // If fetching user fails, redirect to login
      return next({ name: 'Login' });
    }
  }
  
  // If authenticated and going to login/register, redirect to campaigns
  if (auth.token && (to.name === 'Login' || to.name === 'Register')) {
    return next({ name: 'Campaigns' });
  }
  
  // Check admin access for admin routes
  if (to.name === 'Admin') {
    const isAdmin = auth.user && auth.user.role && auth.user.role.name === 'admin';
    if (!isAdmin) {
      // Redirect non-admin users to campaigns page
      return next({ name: 'Campaigns' });
    }
  }
  
  next();
});

export default router; 