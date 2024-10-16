import axios from "axios";
import { useRouter } from "vue-router";

const apiClient = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Add a request interceptor to include the token in requests
apiClient.interceptors.request.use(
    (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

// Add a response interceptor to handle token expiry globally
apiClient.interceptors.response.use(
    (response) => {
    return response;
}, 
(error) => {
    if (error.response && error.response.status === 401) {
        // Token is expired or invalid - log out the user
        const router = useRouter();
        localStorage.removeItem('auth_token');
        router.push({ name: 'Login' }); // Redirect to login
        alert('Session has expired. Please login again.');
    }
    return Promise.reject(error);
});

export default apiClient;