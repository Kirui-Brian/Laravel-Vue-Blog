import { createRouter, createWebHistory } from 'vue-router';
import PostList from '@/views/PostList.vue';
import TagList from '@/views/TagList.vue';
import PostDetails from '@/views/PostDetails.vue';
import PostForm from '@/components/PostForm.vue';
import AuthorPosts from '@/views/AuthorPosts.vue';
import Login from '@/views/Login.vue';
import Logout from '@/views/Logout.vue';

// Route definitions
const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true },
  },
  {
    path: '/logout',
    name: 'Logout',
    component: Logout,
  },
  {
    path: '/postlist',
    name: 'PostList',
    component: PostList,
    meta: { requiresAuth: true }, // Protect this route
  },
  {
    path: '/tags',
    name: 'Tags',
    component: TagList,
  },
  {
    path: '/posts/:id',
    name: 'PostDetails',
    component: PostDetails,
    props: true,
  },
  {
    path: '/create',
    name: 'PostForm',
    component: PostForm,
    meta: { requiresAuth: true },
  },
  {
    path: '/author/:id',
    name: 'AuthorPosts',
    component: AuthorPosts,
    props: true,
    meta: { requiresAuth: true },
  },
];

// Create router instance
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Global Navigation Guard
router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem('auth_token');

  // If trying to access a protected route but not authenticated
  if (to.meta.requiresAuth && !isLoggedIn) {
    next({ name: 'Login' }); // Redirect to login
  } 
  // If trying to access the login page while logged in
  else if (to.meta.requiresGuest && isLoggedIn) {
    next({ name: 'PostList' }); // Redirect to post list
    notify('You are already logged in.');
  } 
  else {
    next(); // Proceed as usual
  }
});

export default router;
