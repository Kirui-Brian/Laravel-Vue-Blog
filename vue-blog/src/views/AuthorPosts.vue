<template>
    <div class="author-posts-container">
        <h1 v-if="authorName">Posts by {{ authorName }}</h1>
        <p v-else>Loading Posts...</p>
        <button @click="deleteSelected" class="bulk-delete-button" :disabled="!selectedPosts.length">Delete Selected</button>
        <div v-if="posts.length > 0">
            <div v-for="post in posts" :key="post?.id" class="post-card">
                <input type="checkbox" v-model="selectedPosts" :value="post.id" />
                <router-link :to="`/posts/${post?.id}`" class="post-title">{{ post?.title }}</router-link>
                <p class="post-body">{{ post?.body }}</p>
                <div class="post-actions">
                    <button @click="editPost(post.id)" class="edit-button">
                        <i class="fa fa-edit"></i> Edit ✏️
                    </button>
                    <button @click="confirmDelete(post.id)" class="delete-button">
                        <i class="fa fa-trash"></i> Delete  🗑️
                    </button>
                </div>
            </div>
        </div>
        <p v-else-if="!error">No posts found for this author.</p>
        <p v-if="error" class="error-message">{{ error }}</p>
        <router-link to="/" class="back-link">Back to Posts</router-link>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AuthorPosts',
    props: ['id'],
    data() {
        return {
            posts: [],
            authorName: '',
            authorId: null,
            error: null,
            selectedPosts: [],
        };
    },
    mounted() {
        this.authorId = this.$route.params.id; // Extract author ID from the route params
        if (this.authorId) {
            this.fetchAuthorPosts();
        } else {
            this.error = 'Author ID is undefined';
        }
    },
    methods: {
        async fetchAuthorPosts(authorID) {
            try {
                //console.log('Fetching posts for author ID:', this.authorId);
                const response = await axios.get(`http://localhost:8000/api/authors/${this.authorId}/posts`);

                // Check if the response contains data
                if (response.data) {
                    this.posts = response.data.posts || [];
                    this.authorName = response.data.name || 'Unknown author';
                } else {
                    this.error = 'No data returned from the server';
                }
            } catch (error) {
                console.error('Error fetching author posts: ' + error);
                this.error = 'An error occurred while fetching posts.';
            }
        },

        editPost(postId) {
            if (postId) {
                // Redirect to edit page or open edit modal
                this.$router.push(`/edit-post/${postId}`);
            } else {
                console.error("Post ID is undefined");
            }
        },

        confirmDeletePost(postId) {
            if (confirm('Are you sure you want to delete this post?')) {
                this.deletePost(postId);
            }
        },

        async deletePost(postId) {
            try {
                await axios.delete(`/api/posts/${postId}`);
                // Remove the deleted post from the list of posts
                this.posts = this.posts.filter(post => post.id !== postId);
            } catch (error) {
                console.error('Error deleting post: ', error);
                alert('Failed to delete the post. Please try again.');
            }
        },

        async deleteSelectedPost() {
            if (confirm('Are you sure you want to delete the selected posts?')) {
                for (const postId of this.selectedPosts) {
                    await this.deletePost(postId);
                }
                this.selectedPosts = []; // Reset selection
            }
        },
    },
};
</script>

<style scoped>
.author-posts-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 2em;
    color: #333;
}

.post-card {
    margin: 15px 0;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #fff;
    transition: transform 0.2s;
}

.post-card:hover {
    transform: scale(1.02);
}

.post-title {
    font-size: 1.5em;
    color: #007bff;
    text-decoration: none;
}

.post-title:visited {
    color: #0056b3;
}

.post-body {
    margin: 10px 0;
    color: #444;
}

.error-message {
    color: red;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.post-actions {
    margin-top: 10px;
}

.edit-button,
.delete-button {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    font-size: 16px;
    margin-right: 10px;
}

.edit-button:hover {
    text-decoration: underline;
}

.delete-button:hover {
    color: red;
}

.bulk-delete-button {
    background-color: red;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 10px;
}
.bulk-delete-button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
</style>