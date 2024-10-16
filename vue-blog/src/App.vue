<script setup>
import { ref } from 'vue';
import { RouterLink, RouterView } from 'vue-router'

// Reactive state for login status
const isLoggedIn = ref(!!localStorage.getItem('auth_token'));

// Update `isLoggedIn` when localStorage changes (login or logout)
const updateLoginStatus = () => {
  isLoggedIn.value = !!localStorage.getItem('auth_token');
};

// Ensure the login status is correct on page reload
updateLoginStatus();

</script>

<template>
  <header>
    <div class="nav-container">
      <nav>
        <!-- Only show the login link if the user is NOT logged in -->
        <RouterLink v-if="!isLoggedIn" to="/" class="nav-link" active-class="active-link">Login</RouterLink>

        <!-- Only show these links if the user is logged in -->
        <RouterLink v-if="isLoggedIn" to="/postlist" class="nav-link" active-class="active-link">View Posts</RouterLink>
        <RouterLink v-if="isLoggedIn" to="/create" class="nav-link" active-class="active-link">Create Post</RouterLink>
        <RouterLink v-if="isLoggedIn" to="/logout" class="nav-link" active-class="active-link">Logout</RouterLink>
      </nav>
    </div>
  </header>

  <RouterView />
</template>

<style scoped>
header {
  background-color: #282c34;
  padding: 20px;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
}

nav {
  display: flex;
  justify-content: space-between;
}

.nav-link {
  color: #61dafb;
  text-decoration: none;
  padding: 10px 15px;
  border-radius: 5px;
  transition: background-color 0.3s;
  font-size: 18px;
  font-weight: bold;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.active-link {
  background-color: rgba(255, 255, 255, 0.2);
  color: #fff;
}
</style>
