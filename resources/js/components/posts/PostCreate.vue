<template>
  <div class="post-create">
    <h2>Créer un nouveau post</h2>
    <form @submit.prevent="sendPost">
      <div>
        <label for="content">Contenu:</label>
        <textarea v-model="content" required></textarea>
      </div>
      <div>
        <label for="image">Image:</label>
        <input name="image" type="file" v-on:change="onChange" placeholder="Choisissez un fichier" />
      </div>
      <div>
        <label for="tags">Tags:</label>
        <input type="text" v-model="tags" placeholder="Tags séparés par des virgules" />
      </div>
      <button type="submit">Créer le post</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from './../../axios';
import { useUserStore } from './../../stores/user';

const content = ref('');
const tags = ref('');
const formData = new FormData();
const userStore = useUserStore();
const validationErrors = ref('');
const router = useRouter();

const onChange = e => {
  let image = e.target.files[0];
  formData.append("image", image);
};

const sendPost = async () => {
  // on ajoute les données saisies au formData (l'image y est déjà)
  formData.append("content", content.value);
  formData.append("tags", tags.value);
  formData.append("user_id", userStore.id);

  // on envoie tout cela vers l'api via un appel axios
  await axios
    .post("/posts", formData)
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
.post-create {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  max-width: 600px;
  margin: 0 auto;
}

form {
  display: flex;
  flex-direction: column;
}

form div {
  margin-bottom: 10px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input,
textarea {
  width: 100%;
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #ddd;
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
