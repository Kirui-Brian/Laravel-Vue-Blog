<template>
    <div class="author-posts-container">
        <h1 v-if="authorName">Posts by {{ authorName }}</h1>
        <p v-else>Loading Posts...</p>

        <button @click="confirmBulkDelete" class="bulk-delete-button" :disabled="!selectedPosts.length">Delete Selected</button>
        
        <div v-if="posts.length > 0">
            <div v-for="post in posts" :key="post?.id" class="post-card">
                <input type="checkbox" v-model="selectedPosts" :value="post.id" />
                <router-link :to="`/posts/${post?.id}`" class="post-title">{{ post?.title }}</router-link>
                <p class="post-body">{{ post?.body }}</p>
                <div class="post-actions">
                    <button @click="openEditModal(post)" class="edit-button">
                        <i class="fa fa-edit"></i> Edit ✏️
                    </button>
                    <button @click="confirmDelete(post)" class="delete-button">
                        <i class="fa fa-trash"></i> Delete  🗑️
                    </button>
                </div>
            </div>
        </div>
        <p v-else-if="!error">No posts found for this author.</p>
        <p v-if="error" class="error-message">{{ error }}</p>

        <router-link to="/" class="back-link">Back to Posts</router-link>

        <!-- Edit Post Modal -->
         <div v-if="isEditModalOpen" class="modal">
            <div class="modal-content">
                <h3>Edit Post</h3>
                <label for="edit-title" class="label">Title:</label>
                <input v-model="editPost.title" id="edit-title" class="input-field" placeholder="Enter Post Title..">

                <label for="edit-body" class="label">Body</label>
                <textarea v-model="editPost.body" id="edit-body" class="textarea-field" placeholder="Write your post content..."></textarea>
                <div class="modal-buttons">
                    <button @click="saveEdit" class="save-button">Save Changes</button>
                    <button @click="closeEditModal" class="cancel-button">Cancel</button>
                </div>
            </div>
         </div>

         <!-- Delete Confirmation Modal -->
          <div v-if="isDeleteModalOpen" class="modal">
            <div class="modal-content">
                <h3>Are you sure you want to delete this post?</h3>
                <button @click="deletePost">Yes, Delete</button>
                <button @click="closeDeleteModal">Cancel</button>
            </div>
          </div>

          <!-- Bulk Delete Confirmation Modal -->
           <div v-if="isBulkDeleteModalOpen" class="modal">
                <div class="modal-content">
                    <h3>Are you sure you want to delete the selected posts?</h3>
                    <button @click="deleteSelectedPosts">Yes, Delete</button>
                    <button @click="closeBulkDeleteModal">Cancel</button>
                </div>
           </div>
    </div>
</template>

<script>
import apiClient from '@/axios';

export default {
    name: 'AuthorPosts',
    props: ['id'],
    data() {
        return {
            posts: [],
            authorName: '',
            authorId: null,
            error: null,
            editPost: {},
            selectedPosts: [],
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            isBulkDeleteModalOpen: false,
            editedPost: null,  // Store the post being edited
            postToDelete: null, // Store the post to delete
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
                const response = await apiClient.get(`/authors/${this.authorId}/posts`);

                // Check if the response contains data
                if (response.data) {
                    this.posts = response.data.posts || [];
                    this.authorName = response.data.name || 'Unknown author';

                    // Sort posts by createdAt date (most recent first)
                    this.posts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                } else {
                    this.error = 'No data returned from the server';
                }
            } catch (error) {
                console.error('Error fetching author posts: ' + error);
                this.error = error.response?.status === 404
                    ? 'Posts not found for this author.'
                    : 'An error occurred while fetching posts.';
            }
        },

        openEditModal(post) {
            this.editPost = { ...post }; // Create a copy of the post to edit
            this.isEditModalOpen = true;
        },

        closeEditModal() {
            this.isEditModalOpen = false;
            this.editPost = {};
        },

        saveEdit() {
            // Here send the request to the backend to update the post
            apiClient.put(`/posts/${this.editPost.id}`, this.editPost)
                .then(response => {
                    // Update the post in the list of posts
                    const postIndex = this.posts.findIndex(post => post.id === this.editPost.id);
                    if (postIndex !== -1) {
                        this.posts[postIndex] = { ...this.editPost };
                    }
                    this.closeEditModal();
                }).catch (error => {
                    console.error('Failed to update post:', error);
                });
        },

        confirmDelete(post) {
            this.postToDelete = post;
            this.isDeleteModalOpen = true;
        },

        closeDeleteModal() {
            this.isDeleteModalOpen = false;
            this.postToDelete = null;
        },

        async deletePost() {
            await apiClient.delete(`/posts/${this.postToDelete.id}`)
                .then(response => {
                    this.posts = this.posts.filter(post => post.id !== this.postToDelete.id);
                    this.closeDeleteModal();
                })
                .catch(error => {
                    console.error('Error deleting post:', error);
                    //alert('Failed to delete the post. Please try again.');
                });
        },

        confirmBulkDelete() {
            this.isBulkDeleteModalOpen = true;
        },

        closeBulkDeleteModal() {
            this.isBulkDeleteModalOpen = false;
        },

        async deleteSelectedPosts() {
            try {
                for (const postId of this.selectedPosts) {
                    apiClient.delete(`/posts/${postId}`)
                        .then(() => {
                            this.posts = this.posts.filter(post => post.id !== postId);
                            }).catch(error => {
                                console.error('Error Deleting Post.', error);
                                });
                            }
                        } catch (error) {
                            console.error('Error deleting selected posts:', error);
                        }
                        this.selectedPosts = [];
                        this.closeBulkDeleteModal();
        },
    },
};
</script>

<style scoped>
.author-posts-container {
    max-width: 800px;
    width: 90%;
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

.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 90%;
    color: #333;
}

.label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

.input-field,
.textarea-field {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.input-field:focus,
.textarea-field:focus {
    border-color: #007bff;
    outline: none;
}

.textarea-field {
    height: 100px;
    resize: none;
}

.modal-buttons {
    display: flex;
    justify-content: space-between;
}

.save-button,
.cancel-button {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16;
    transition: background-color 0.3s;
}

.save-button {
    background-color: #007bff;
    color: white;
}

.cancel-button {
    background-color: #f44336;
    color: white;
}

.save-button:hover {
    background-color: #0056b3;
}

.cancel-button:hover {
    background-color: #c62828;
}

h3 {
    margin-bottom: 20px;
    font-weight: bold;
}

p {
    font-weight: normal;
}

button {
    margin-right: 10px;
}

p[v-else-if="!error"] {
    font-size: 1.2em;
    color: #666;
    font-style: italic;
    text-align: center;
    margin-top: 20px;
    opacity: 0.9;
}

p[v-else-if="!error"]:hover {
    color: #333;
    opacity: 1;
}

/* Responsive styles */
@media (max-width: 600px) {
    h1 {
        font-size: 1.5em; /* Smaller heading on mobile */
    }

    .post-card {
        padding: 10px; /* Less padding on post cards */
    }

    .post-title {
        font-size: 1.2em; /* Smaller title size */
    }

    .post-body {
        font-size: 14px; /* Smaller body text */
    }

    .edit-button,
    .delete-button {
        font-size: 14px; /* Smaller button text */
    }

    .bulk-delete-button {
        font-size: 14px; /* Smaller button text for bulk delete */
    }

    .modal-content {
        padding: 15px; /* Less padding in modals on mobile */
    }
}

</style>