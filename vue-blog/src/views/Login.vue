<template>
  <div class="auth-container">
    <h1>{{ isLogin ? 'Login' : 'Create Account' }}</h1>
    <div v-if="errorMessage" class="error-notification">{{ errorMessage }}</div>
    <div v-if="successMessage" class="success-notification">{{ successMessage }}</div>
    <form @submit.prevent="handleSubmit">
      <div v-if="!isLogin" class="name-fields">
        <input
          v-model="form.first_name"
          type="text"
          placeholder="First Name"
          v-if="!isLogin"
          required
        />
        <input
          v-model="form.last_name"
          type="text"
          placeholder="Last Name"
          v-if="!isLogin"
          required
        />
      </div>

      <input
        v-model="form.email"
        type="email"
        placeholder="Email"
        required
      />
      <input
        v-model="form.password"
        type="password"
        placeholder="Password"
        required
        :minlength="8"
      />
      <input
        v-if="!isLogin"
        v-model="form.password_confirmation"
        type="password"
        placeholder="Confirm Password"
        required
        :minlength="8"
      />

      <div class="auth-actions">
        <button type="submit">
          {{ isLogin ? 'Login' : 'Register' }}
        </button>
        <button type="button" @click="toggleAuthMode">
          {{ isLogin ? 'Create Account' : 'Already have an account? Login' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '@/axios';

export default {
    name: 'Login',
    setup() {
        const router = useRouter();
        const isLogin = ref(true); // Toggle between Login and Register modes
        const form = ref({
            first_name: '',
            last_name: '',
            email: '',
            password: '',
            password_confirmation: ''
        });

        const errorMessage = ref('');
        const successMessage = ref('');
        const isLoggedIn = ref(!!localStorage.getItem('auth_token'));

        const toggleAuthMode = () => {
            isLogin.value = !isLogin.value;
            };

        const handleSubmit = async () => {
            try {
                errorMessage.value = '';
                successMessage.value = '';

                if (!isLogin.value && form.value.password !== form.value.password_confirmation) {
                    alert('Passwords do not match!');
                    return;
                    }

            let response;

            if (isLogin.value) {
                // Login request
                response = await apiClient.post('/login', {
                    email: form.value.email,
                    password: form.value.password
                });

                // Store token after successful login
                localStorage.setItem('auth_token', response.data.access_token);
                successMessage.value = 'Login successful! Redirecting to posts...';
             
                setTimeout(() =>{
                    router.push({ name: 'PostList' });
                }, 1000);
                
            } else {
                // Registration request
                response = await apiClient.post('/register', {
                    first_name: form.value.first_name,
                    last_name: form.value.last_name,
                    email: form.value.email,
                    password: form.value.password,
                    password_confirmation: form.value.password_confirmation
                });

                // Clear form fields after successful submission
                form.value.first_name = '';
                form.value.last_name = '';
                form.value.email = '';
                form.value.password = '';
                form.value.password_confirmation = '';

                /// Switch to login mode and inform the user
                isLogin.value = true;
                successMessage.value = 'Registration successful! Please log in.';
                setTimeout(() => {
                    router.push({ name: 'Login' });
                }, 2000);
            }
            
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    errorMessage.value = 'Invalid credentials. Please check your email and password.';
                } else if  (error.response && error.response.data.message) {
                    errorMessage.value = error.response.data.message
                } else {
                    errorMessage.value = 'An unexpected error occurred';
                }
                console.error('Error during authentication', error);
            }
        };

        return {
            isLogin,
            form,
            toggleAuthMode,
            handleSubmit,
            errorMessage,
            successMessage,
            isLoggedIn
        };
    }
};
</script>

<style scoped>
.auth-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.auth-container h1 {
  text-align: center;
  color: black;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.auth-actions {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

button {
  padding: 10px 15px;
  background-color: #07bf9b;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #06ac8b;
}

.error-notification {
    color: red;
    text-align: center;
    margin-bottom: 15px;
}

.success-notification {
    color: green;
    text-align: center;
    margin-bottom: 15px;
}
</style>
