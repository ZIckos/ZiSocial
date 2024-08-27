<template>
  <div class="post-update">
    <h3>Modifier le post</h3>
    <form @submit.prevent="updatePost">
      <div>
        <label for="content">Contenu:</label>
        <textarea v-model="props.post.content" id="content"></textarea>
      </div>
      <div>
        <label for="image">Image:</label>
        <input name="image" type="file" v-on:change="onChange" placeholder="Choisissez un fichier" />
      </div>
      <div>
        <label for="tags">Tags:</label>
        <input v-model="props.post.tags" id="tags"/>
      </div>
      <button type="submit">Enregistrer</button>
      <button type="button" @click="cancelEdit">Annuler</button>
    </form>
  </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import { useRouter } from 'vue-router';
import axios from './../../axios';

const formData = new FormData();
const validationErrors = ref('');
const router = useRouter();
const props = defineProps(["post"]);


const onChange = e => {
  let image = e.target.files[0];
  formData.append("image", image);
};

const updatePost = async () => {
  // on ajoute les données saisies au formData (l'image y est déjà)
  formData.append("content", props.post.content);
  formData.append("tags", props.post.tags);
  formData.append("_method", "PUT");

  // on envoie tout cela vers l'api via un appel axios
  await axios
    .post(`/posts/${props.post.id}`, formData)
    .then((response) => {
      alert("Message créé avec succès !");
      router.push("/posts"); // force la page à se recharger pour récupérer la nouvelle liste des posts
    })
    .catch((error) => {
      validationErrors.value = error.response.errors;
    });
}
</script>

<style scoped>
.post-update {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

form {
  display: flex;
  flex-direction: column;
}

label {
  margin-top: 10px;
}

input,
textarea {
  margin-top: 5px;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

button {
  margin-top: 10px;
  padding: 8px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button[type="submit"] {
  background-color: #007bff;
  color: white;
}

button[type="button"] {
  background-color: #6c757d;
  color: white;
}
</style>
