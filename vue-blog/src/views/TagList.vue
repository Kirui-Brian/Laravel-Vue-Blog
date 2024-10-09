<template>
    <div>
        <h1>Tags</h1>
        <ul>
            <li v-for="tag in tags" :key="tag.id">{{ tag.name }}</li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Tags',
    data() {
        return {
            tags: [],
        };
    },
    mounted() {
        this.fetchTags();
    },
    methods: {
        async fetchTags() {
            try {
                const response = await axios.get('http://localhost:8000/api/tags');
                this.tags = response.data.data;
            } catch (error) {
                console.error('Error fetching tags:', error);
            }
        }
    }
}
</script>