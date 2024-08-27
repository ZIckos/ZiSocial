<template>
  <div class="comment-form">
    <h3>Ajouter un commentaire</h3>
    <form @submit.prevent="addComment">
      <textarea v-model="content" placeholder="Écrivez votre commentaire..." required></textarea>
      <button type="submit">Ajouter</button>
      <p v-if="error" class="error-message">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from './../../axios';
import { useUserStore } from './../../stores/user';

const props = defineProps({
  postId: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['comment-added']);
const content = ref('');
const error = ref('');

const addComment = async () => {
  try {
    const userStore = useUserStore();
    await axios.post(`/posts/${props.postId}/comments`, {
      content: content.value,
    }, {
      headers: {
        'Authorization': `Bearer ${userStore.token}`,
      },
    });
    emit('comment-added'); 
    content.value = ''; 
  } catch (error) {
    error.value = 'Erreur lors de l\'ajout du commentaire.';
  }
};
</script>
  
  <style scoped>
  .comment-form {
    margin-top: 20px;
  }
  
  textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  button {
    margin-top: 10px;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    cursor: pointer;
  }
  
  button:hover {
    opacity: 0.9;
  }
  
  .error-message {
    color: red;
  }
  </style>
  