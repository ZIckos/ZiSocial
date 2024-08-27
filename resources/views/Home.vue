<template>
  <div class="container mt-5">
    <h2 class="mb-4">Derniers Posts</h2>
    <div v-if="latestPosts.length" class="row">
      <div v-for="post in latestPosts" :key="post.id" class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <img :src="`images/${post.image}`" alt="" style="height: 150px;">
            <h5 class="card-title">{{ post.title }}</h5>
            <p class="card-text">{{ post.content }}</p>
            <router-link :to="`/posts/${post.id}`" class="btn btn-primary">Lire la suite</router-link>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Aucun post trouvé.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Déclaration de la variable réactive
const latestPosts = ref([]);

// Fonction pour récupérer les derniers posts
const fetchLatestPosts = async () => {
  try {
    const response = await axios.get('/latest-posts');
    latestPosts.value = response.data;
  } catch (error) {
    console.error('Erreur lors de la récupération des derniers posts:', error);
  }
};

// Appel de la fonction lorsque le composant est monté
onMounted(fetchLatestPosts);
</script>

<style scoped></style>
