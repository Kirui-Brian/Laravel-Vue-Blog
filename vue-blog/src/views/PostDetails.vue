<template>
    <div v-if="post" class="post-details-container">
        <h1>{{ post.title }}</h1>
        <p>{{ post.body }}</p>
        <p><strong>Author:</strong> <router-link :to="`/author/${post.author.id}`">{{ post.author.name }}</router-link></p>
        <p><strong>Tags:</strong> <span v-for="tag in post.tags" :key="tag.id">{{ tag.name }}</span></p>
        <router-link to="/">Back to Posts</router-link>
    </div>
</template>

<script>
import apiClient from "@/axios";

export default {
    name: 'PostDetails',
    props: {
        id: {
            type: String,
            required: true,
        }
    },
    data() {
        return {
            post: null,
        };
    },
    async created() {
        await this.fetchPost();
    },
    methods: {
        async fetchPost() {
            try {
                const response = await apiClient.get(`http://localhost:8000/api/posts/${this.id}`);
                this.post = response.data;
            } catch (error) {
                console.error('Error fetching posts', error);
                this.post = {};
            }
        },
    },
};
</script>

<style scoped>
.post-details-container {
    max-width: 1000px;
    width: 90%;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    color: #222;
}

h1 {
    font-size: 2.5em;
    color: #333;
}

strong {
    color: #333;
    font-style: italic;
}

.router-link {
    color: #00fb;
    text-decoration: none;
    font-weight: bold;
}

.router-link:hover {
    text-decoration: underline;
}

.tags {
    display: inline-block;
    margin: 5px 0;
}

.tag {
    display: inline-block;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 3px;
    background-color: #00fb;
    color: rgb(44, 42, 42);
}

/* Responsive styles */
@media (max-width: 600px) {
    h1 {
        font-size: 2em; /* Smaller heading size for mobile */
    }

    .post-details-container {
        padding: 15px; /* Less padding for smaller screens */
    }

    strong {
        font-size: 16px; /* Adjusted font size for strong text */
    }

    .router-link {
        font-size: 14px; /* Smaller link text */
    }

    .tag {
        padding: 4px 8px; /* Smaller padding for tags */
        margin: 4px; /* Adjusted margin for tags */
        font-size: 14px; /* Smaller font size for tags */
    }
}
</style>