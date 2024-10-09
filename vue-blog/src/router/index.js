import { createRouter, createWebHistory } from 'vue-router';
import PostList from '@/views/PostList.vue';
import TagList from '@/views/TagList.vue';
import PostDetails from '@/views/PostDetails.vue';
import PostForm from '@/components/PostForm.vue';
import AuthorPosts from '@/views/AuthorPosts.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
      {
      path: '/',
      name: 'PostList',
      component: PostList,
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
      component: PostForm
    },
    { path: '/author/:id',
      name: 'AuthorPosts',
      component: AuthorPosts,
      props: true,
    },
  ]
})

export default router;
