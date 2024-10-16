<template>
    <div class="logout-container">
        <p>You are being logged out...</p>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

const router = useRouter();

onMounted(() => {
    localStorage.removeItem('auth_token'); // Remove the token
    setTimeout(() =>{
        router.push({ name: 'Login' }); // Redirect to login after logout
    }, 1000);
});
</script>

<style scoped>
.logout-container {
    text-align: center;
    margin-top: 50px;
}
</style>