<template>
  <div class="post-delete">
    <h3>Supprimer le post</h3>
    <p>Êtes-vous sûr de vouloir supprimer ce post ?</p>
    <button @click="deletePost">Valider</button>
    <button @click="cancelDelete">Annuler</button>
  </div>
</template>

<script setup>
import axios from './../../axios';

const props = defineProps({
  postId: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['post-deleted', 'cancel-delete']);

const deletePost = async () => {
  try {
    await axios.delete(`/posts/${props.postId}`);
    emit('post-deleted');
  } catch (error) {
    console.error('Erreur lors de la suppression du post:', error);
  }
};

const cancelDelete = () => {
  emit('cancel-delete');
};
</script>


<style scoped>
.post-delete {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

button {
  margin-top: 10px;
  padding: 8px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:first-of-type {
  background-color: #dc3545;
  color: white;
}

button:last-of-type {
  background-color: #6c757d;
  color: white;
}
</style>
