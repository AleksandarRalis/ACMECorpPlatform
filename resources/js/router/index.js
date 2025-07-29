import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Campaigns from '../views/Campaigns.vue';
import CampaignDetail from '../views/CampaignDetail.vue';
import CreateCampaign from '../views/CreateCampaign.vue';
import Admin from '../views/Admin.vue';
import { useAuthStore } from '../stores/auth';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
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

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const auth = useAuthStore();
  // If not authenticated and not going to login/register, redirect to login
  if (!auth.token && to.name !== 'Login' && to.name !== 'Register') {
    return next({ name: 'Login' });
  }
  // If authenticated and going to login/register, redirect to home
  if (auth.token && (to.name === 'Login' || to.name === 'Register')) {
    return next({ name: 'Home' });
  }
  next();
});

export default router; 