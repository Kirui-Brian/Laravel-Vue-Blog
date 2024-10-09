<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <header>

    <div class="nav-container">
      <nav>
        <RouterLink to="/" class="nav-link" active-class="active-link">Home</RouterLink>
        <RouterLink to="/Create" class="nav-link" active-class="active-link">Create Post</RouterLink>
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
