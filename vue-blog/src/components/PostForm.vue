<template setup>
    <div class="post-form">
        <h2>Create Post</h2>
        <form @submit.prevent="createPost">
            <div class="form-group">
                <label for="title">Title:</label>
                <input 
                    v-model="title"
                    type="text"
                    id="title"
                    placeholder="Enter Post Title" 
                    required
                    minlength="3"
                    class="form-input"
                />
            </div>

            <div class="form-group">
                <label for="body">Body:</label>
                <textarea
                    v-model="body"
                    id="body"
                    placeholder="Write your post content here."
                    required
                    minlength="10"
                    class="form-textarea"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="author">Author:</label>
                <select v-model="author_id" id="author" v-if="authors.length > 0" class="form-select">
                    <option value="" disabled>Select an author</option>
                    <option v-for="author in authors" :key="author?.id" :value="author?.id">
                        {{ author?.name }}
                    </option>
                </select>
                <p v-else>Loading authors...</p>
            </div>

            <div class="form-group">
                <label>Tags:</label>
                <div class="tags-container" v-if="tags.length">
                    <div v-for="tag in tags" :key="tag?.id" class="tag-item">
                        <input type="checkbox" :value="tag?.id" v-model="selectedTags" />
                        <label>{{ tag?.name }}</label>
                    </div>
                </div>
                <p v-else>Loading tags...</p>
            </div>

            <button type="submit" :disabled="isSubmitting" class="submit-button">
                Create Post
            </button>
        </form>
        <p v-if="successMessage" class="success-message">{{ successMessage }}</p>
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'; // Importing necessary functions from Vue
import { useRouter } from 'vue-router'; // Importing useRouter for navigation
import apiClient from '@/axios'; // Importing axios client

export default {
    name: 'PostForm',
    setup() {
        const router = useRouter(); // Get the router instance
        // Defining reactive state variables
        const title = ref('');
        const body = ref('');
        const author_id = ref('');
        const tags = ref([]);
        const authors = ref([]);
        const selectedTags = ref([]);
        const isSubmitting = ref(false);
        const successMessage = ref('');
        const errorMessage = ref('');

        // Method to load authors
        const loadAuthors = () => {
            apiClient.get('/authors')
                .then(response => {
                    authors.value = response.data; // Update authors state
                })
                .catch(error => {
                    console.error('Error loading authors:', error);
                });
        };

        // Method to load tags
        const loadTags = () => {
            apiClient.get('/tags')
                .then(response => {
                    tags.value = response.data; // Update tags state
                })
                .catch(error => {
                    console.error("Error loading tags: ", error);
                });
        };

        // Method to create a post
        const createPost = () => {
            isSubmitting.value = true; // Disable the button

            apiClient.post('/posts', {
                title: title.value,
                body: body.value,
                author_id: author_id.value,
                tags: selectedTags.value
            })
            .then(response => {
                successMessage.value = 'Post created successfully!';
                resetForm(); // Reset form fields
                errorMessage.value = ''; // Reset error message on success

                // Set timeout to redirect to /postlist after 2 seconds
                setTimeout(() => {
                    successMessage.value = ''; // Clear success message
                    router.push({ name: 'PostList' }); // Redirect to /postlist
                }, 2000);
            })
            .catch(error => {
                console.error(error);
                errorMessage.value = 'Failed to create post. Please try again.'; // Display error message
                successMessage.value = ''; // Reset success message on error
            })
            .finally(() => {
                isSubmitting.value = false; // Re-enable the button
            });
        };

        // Method to reset form fields
        const resetForm = () => {
            title.value = '';
            body.value = '';
            author_id.value = '';
            selectedTags.value = [];
        };

        // Lifecycle hook to load authors and tags on component mount
        onMounted(() => {
            loadAuthors(); // Load authors when the component is created
            loadTags(); // Load tags when the component is created
        });

        // Return all reactive variables and methods to the template
        return {
            title,
            body,
            author_id,
            tags,
            authors,
            selectedTags,
            isSubmitting,
            successMessage,
            errorMessage,
            createPost
        };
    }
};
</script>

<style scoped>
.post-form {
    max-width: 800px;
    width: 90%;
    height: auto;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
    font-weight: bold;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #444;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    color: #333;
}

.form-textarea {
    height: 150px;
    resize: vertical;
}

.tags-container {
    display: flex;
    flex-wrap: wrap;
}

.tag-item {
    margin-right: 15px;
    color: #999;
}

.submit-button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

.submit-button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.success-message {
    color: green;
    text-align: center;
    font-size: 18px;
    margin-top: 10px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: 50%;
    transform: translate(-50%, -50%);
}

.error-message {
    color: red;
    text-align: center;
    margin-top: 10px;
}

/* Responsive styles */
@media (max-width: 600px) {
    .post-form {
        padding: 15px; /* Reduce padding for smaller screens */
        margin: 10px; /* Reduce margin */
    }

    .form-input,
    .form-textarea,
    .form-select {
        font-size: 14px; /* Smaller font for inputs */
    }

    .submit-button {
        font-size: 14px; /* Smaller button text */
    }

    .success-message,
    .error-message {
        font-size: 16px; /* Adjust size for success/error messages */
    }
}
</style>
