<template>
  <div v-if="post" class="post-detail">
    <h2>{{ isEditing ? 'Modifier le post' : post.title }}</h2>

    <!-- Affichage ou Formulaire de Modification -->
    <PostUpdate v-if="isEditing" :post="post" @post-updated="handlePostUpdated" @cancel-edit="cancelEdit" />

    <!-- Affichage du post -->
    <div v-else>
      <div class="post-image" :style="{ backgroundImage: `url(/images/${post.image})` }"></div>
      <div class="post-content">
        <p>{{ post.content }}</p>
      </div>
      <div class="post-tags">
        <strong>Tags:</strong> {{ post.tags }}
      </div>

      <!-- Afficher les boutons seulement si l'utilisateur est le créateur du post -->
      <button v-if="canEdit" @click="startEdit" class="edit-button">Modifier</button>
      <button v-if="canEdit" @click="showDeleteConfirmation" class="delete-button">Supprimer</button>
      <button @click="goBack" class="back-button">Retour</button>
    </div>

    <!-- Affichage des Commentaires -->
    <CommentList :postId="post.id" ref="commentList" />

    <!-- Formulaire pour ajouter un commentaire -->
    <CommentCreate :postId="post.id" @comment-added="fetchComments" />

    <!-- Confirmation de Suppression -->
    <PostDelete v-if="isDeleting" :postId="post.id" @post-deleted="handlePostDeleted" @cancel-delete="cancelDelete" />
  </div>
  <div v-else>
    <p>Chargement du post...</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from './../../axios';
import { useUserStore } from './../../stores/user';
import PostUpdate from './PostUpdate.vue';
import PostDelete from './PostDelete.vue';
import CommentList from '../comments/CommentList.vue';
import CommentCreate from '../comments/CommentCreate.vue'; 

const props = defineProps({
  postId: {
    type: String,
    required: true,
  },
});

const post = ref(null);
const isEditing = ref(false);
const isDeleting = ref(false);
const canEdit = ref(false); // Utiliser un booléen
const userStore = useUserStore();
const router = useRouter();
const commentList = ref(null);

const fetchPost = async () => {
  try {
    const response = await axios.get(`/posts/${props.postId}`);
    post.value = response.data;
    checkPermissions();
  } catch (error) {
    console.error('Erreur lors de la récupération du post:', error);
  }
};

const checkPermissions = () => {
  const user = userStore.user;
  if (post.value && user) {
    canEdit.value = post.value.user_id === user.id;
  }
};

const startEdit = () => {
  isEditing.value = true;
};

const cancelEdit = () => {
  isEditing.value = false;
};

const handlePostUpdated = (updatedPost) => {
  post.value = updatedPost;
  isEditing.value = false;
};

const showDeleteConfirmation = () => {
  isDeleting.value = true;
};

const cancelDelete = () => {
  isDeleting.value = false;
};

const handlePostDeleted = () => {
  router.push({ name: 'Posts' });
};

const goBack = () => {
  router.push({ name: 'Posts' });
};

const fetchComments = () => {
  commentList.value?.fetchComments();
};

onMounted(fetchPost);
</script>

<style scoped>
.post-detail {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  margin: 0 auto;
}

.post-image {
  width: 100%;
  height: 300px;
  background-size: cover;
  background-position: center;
  border-radius: 10px;
  margin-top: 20px;
}

.post-content {
  margin-top: 20px;
}

.post-tags {
  margin-top: 10px;
}

button {
  margin-top: 10px;
  padding: 4px 8px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.edit-button {
  margin-right: 10px;
  background-color: #007bff;
  color: white;
}

.delete-button {
  background-color: #dc3545;
  color: white;
}

.back-button {
  background-color: #6c757d;
  color: white;
}

button:hover {
  opacity: 0.9;
}
</style>
