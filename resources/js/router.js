// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Connect from '../views/Connect.vue';
import Profile from '../views/Profil.vue';
import Posts from '../views/Post.vue';
import PostDetail from '../js/components/posts/PostDetail.vue';
import PostCreate from '../js/components/posts/PostCreate.vue';
import { useUserStore } from '../js/stores/user';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/connect',
    name: 'Connect',
    component: Connect,
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
    meta: { requiresAuth: true },
  },

  {
    path: '/posts',
    name: 'Posts',
    component: Posts,
    meta: { requiresAuth: true },
  },
  {
    path: '/posts/:postId',
    name: 'PostDetail',
    component: PostDetail,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: '/posts/create',
    name: 'PostCreate',
    component: PostCreate,
    meta: { requiresAuth: true },
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const userStore = useUserStore();
  const isAuthenticated = !!userStore.token;

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/connect'); 
  } else if (to.meta.requiresGuest && isAuthenticated) {
    next('/'); 
  } else {
    next();
  }
});

export default router;
