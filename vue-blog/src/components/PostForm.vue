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
import axios from 'axios';

export default {
    name: 'PostForm',
    data() {
        return {
            title: '',
            body: '',
            author_id: '',
            tags: [],
            authors: [],
            selectedTags: [],
            isSubmitting: false,
            successMessage: '',
            errorMessage: ''
        };
    },
    created() {
        this.loadAuthors();
        this.loadTags();
    },
    methods: {
        loadAuthors() {
            axios.get('http://localhost:8000/api/authors')
                .then(response => {
                    this.authors = response.data;
                }).catch(error => {
                    console.error('Error loading authors:', error);
                });
        },
        loadTags() {
            axios.get('http://127.0.0.1:8000/api/tags')
                .then(response => {
                    this.tags = response.data;
                }).catch(error => {
                    console.error("Error loading tags: ", error);
                });
        },
        createPost() {
            this.isSubmitting = true; // Disable the button

            axios.post('http://127.0.0.1:8000/api/posts', {
                title: this.title,
                body: this.body,
                author_id: this.author_id,
                tags: this.selectedTags
            }).then(response => {
                this.successMessage = 'Post created successfully!';
                this.resetForm();
                this.errorMessage = ''; // Reset error message on success

                // Set timeout to reset the success message after 2 seconds
                setTimeout(() => {
                    this.successMessage = '';
                }, 2000);
            }).catch(error => {
                console.error(error);
                this.errorMessage = 'Failed to create post. Please try again.'; // Display error message
                this.successMessage = ''; // Reset success message on error
            }).finally(() => {
                this.isSubmitting = false; // Re-enable the button
            });
        },
        resetForm() {
            this.title = '';
            this.body = '';
            this.author_id = '';
            this.selectedTags = [];
        }
    }
};
</script>

<style scoped>
.post-form {
    max-width: 800px;
    margin: 0 auto;
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
</style>
