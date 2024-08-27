<template>
  <header>
    <h1>Posts</h1>
    <button @click="createPost" class="create-button">Créer un nouveau post</button>
  </header>
  <div class="post-list">
    <div
      v-for="post in posts"
      :key="post.id"
      class="post-item"
      :style="{ backgroundImage: `url(/images/${post.image})` }"
    >
      <div class="post-overlay">
        <h2>{{ post.tags }}</h2>
        <button @click="viewPost(post.id)">Voir plus</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from './../../axios';

const posts = ref([]);
const router = useRouter();

const fetchPosts = async () => {
  try {
    const response = await axios.get('/posts');
    posts.value = response.data;
    console.log(response.data)
    console.log(posts.value)
  } catch (error) {
    console.error('Erreur lors de la récupération des posts:', error);
  }
};

const createPost = () => {
  router.push({ name: 'PostCreate' });
};

const viewPost = (id) => {
  router.push({ name: 'PostDetail', params: { postId: id } });
};

onMounted(fetchPosts);
</script>

<style scoped>
header {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.create-button {
  background-color: #28a745;
  border: none;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 20px;
  position: relative;
}

.create-button:hover {
  background-color: #218838;
}

.post-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.post-item {
  width: 300px;
  height: 200px;
  background-size: cover;
  background-position: center;
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.post-overlay {
  position: absolute;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  color: #fff;
  width: 100%;
  text-align: center;
  padding: 10px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.post-item:hover .post-overlay {
  opacity: 1;
}

button {
  background-color: #007bff;
  border: none;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
