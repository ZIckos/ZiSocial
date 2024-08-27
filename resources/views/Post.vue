<template>
  <div>
    <PostList @view-post="handleViewPost" />
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import PostList from '../js/components/posts/PostList.vue';

// Initialisation du routeur
const router = useRouter();

// Fonction pour gérer la vue du post
function handleViewPost(postId) {
  router.push({ name: 'PostDetail', params: { id: postId } });
}
</script>

<style scoped>
h1 {
  text-align: center;
  margin-bottom: 20px;
}
</style>
