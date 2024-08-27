<template>
  <div class="comment-list">
    <div v-if="comments.length === 0">
      <p>Aucun commentaire pour ce post.</p>
    </div>
    <div v-else>
      <div v-for="comment in comments" :key="comment.id" class="comment-item">
        <div class="comment-author">{{ comment.user.pseudo }}</div>
        <div class="comment-content">
          <div v-if="isEditingComment(comment.id)">
            <CommentUpdate :commentData="currentComment" @comment-updated="handleCommentUpdated"
              @cancel-edit="cancelEdit" />
          </div>
          <div v-else>
            {{ comment.content }}
          </div>

          <div v-if="isDeleting && currentComment.id === comment.id">
            <CommentDelete :commentId="currentComment.id" @comment-deleted="handleCommentDeleted"
              @cancel-delete="cancelDelete" />
          </div>
        </div>
        <div v-if="canEdit(comment)" style="display: flex; gap: 1em; padding: 10px;">
          <button @click="startEditComment(comment)" class="button-update">Modifier</button>
          <button @click="showDeleteConfirmation(comment)" class="button-delete">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from './../../axios';
import CommentUpdate from './CommentUpdate.vue';
import CommentDelete from './CommentDelete.vue';
import { useUserStore } from './../../stores/user';

const props = defineProps({
  postId: {
    type: Number,
    required: true,
  },
});

const comments = ref([]);
const isEditingCommentId = ref(null);
const isDeleting = ref(false);
const currentComment = ref(null);

const userStore = useUserStore();

const fetchComments = async () => {
  try {
    const response = await axios.get(`/posts/${props.postId}/comments`);
    comments.value = response.data;
  } catch (error) {
    console.error('Erreur lors de la récupération des commentaires:', error);
  }
};

const startEditComment = (comment) => {
  isEditingCommentId.value = comment.id;
  currentComment.value = { ...comment };
};

const isEditingComment = (commentId) => {
  return isEditingCommentId.value === commentId;
};

const handleCommentUpdated = (updatedComment) => {
  const index = comments.value.findIndex(c => c.id === updatedComment.id);
  if (index !== -1) {
    comments.value[index] = updatedComment;
  }
  isEditingCommentId.value = null;
};

const showDeleteConfirmation = (comment) => {
  isDeleting.value = true;
  currentComment.value = { ...comment };
};

const handleCommentDeleted = () => {
  comments.value = comments.value.filter(c => c.id !== currentComment.value.id);
  isDeleting.value = false;
  currentComment.value = null;
};

const cancelEdit = () => {
  isEditingCommentId.value = null;
};

const cancelDelete = () => {
  isDeleting.value = false;
  currentComment.value = null;
};

const canEdit = (comment) => {
  return comment.user_id === userStore.user.id;
};

onMounted(fetchComments);
</script>


<style scoped>
.comment-list {
  margin-top: 20px;
}

.comment-item {
  padding: 10px;
  border-bottom: 1px solid #ddd;
}

.comment-author {
  font-weight: bold;
}

.comment-content {
  margin-top: 5px;
}

button {
  padding: 4px 8px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.button-update {
  background-color: #084f9b;
  color: white;
}

.button-update:hover {
  background-color: #007bff;
}

.button-delete {
  background-color: rgb(209, 23, 23);
  color: white;
}

.button-delete:hover {
  background-color: rgb(122, 12, 12);
}

.error-message {
  color: red;
}
</style>