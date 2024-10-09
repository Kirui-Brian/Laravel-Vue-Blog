<template>
    <div class="post-list-container">
        <h1>Blog Posts</h1>
        <div v-for="post in posts" :key="post?.id" class="post-card">
            <router-link v-if="post?.id" :to="`/posts/${post.id}`" class="post-title">
                {{ post?.title }}
            </router-link>
            <p class="post-body">{{ post?.body }}</p>
            <p class="post-author">
                <strong>Author:</strong>
                <span v-if="post?.author?.id">
                    <router-link :to="`/author/${post.author.id}`">
                        {{ post?.author?.name }}
                    </router-link>
                </span>
                <span v-else>No Author Available</span>
            </p>
            <p class="post-tags">
                <strong>Tags:</strong>
                <span v-if="post?.tags.length">
                    <span v-for="(tag, index) in post.tags" :key="tag.id" class="tag">
                        {{ tag.name }}<span v-if="index !== post.tags.length - 1">,</span>
                    </span>
                </span>
                <span v-else>No Tags Available</span>
            </p>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'PostList',
    data() {
        return {
            posts: [],
        };
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        async fetchPosts() {
            try {
                const response = await axios.get('http://localhost:8000/api/posts');
                this.posts = response.data;
                //console.log(this.posts);

                this.posts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            } catch (error) {
                console.error('Error fetching posts: ' + error);
            }
        },
    },
};
</script>

<style scoped>
.post-list-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 8px;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
    font-size: 32px;
    font-weight: bold;
}

.post-card {
    background-color: white;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #eee;
    transition: transform 0.2s;
}

.post-card:hover {
    transform: translateY(-2px);
}

.post-title {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
    font-weight: bold;
}

.post-body {
    font-size: 16px;
    color: #666;
    margin-bottom: 15px;
    line-height: 1.6;
}

.post-author,
.post-tags {
    font-size: 14px;
    color: #777;
    margin-bottom: 10px;
}

.tag {
    display: inline-block;
    background-color: #e0e0e0;
    padding: 3px 8px;
    margin-right: 5px;
    border-radius: 4px;
    font-size: 0.85em;
}

.tag:last-child {
    margin-right: 0;
}

/* Responsive design */
@media (max-width: 600px) {
    .post-list-container {
        padding: 15px;
    }

    .post-title {
        font-size: 20px;
    }

    .post-body {
        font-size: 14px;
    }

    .post-author,
    .post-tags {
        font-size: 12px;
    }
}
</style>