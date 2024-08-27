<template>
  <div class="signup-container">
    <h1>Inscription</h1>
    <form @submit.prevent="signup" class="signup-form">
      <div class="form-group">
        <label for="pseudo">Pseudo:</label>
        <input v-model="pseudo" type="text" id="pseudo" placeholder="Entrez votre pseudo" required />
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input v-model="email" type="email" id="email" placeholder="Entrez votre email" required />
      </div>
      <div class="form-group">
        <label for="password">Mot de passe:</label>
        <input v-model="password" type="password" id="password" placeholder="Entrez votre mot de passe" required />
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirmer le mot de passe:</label>
        <input v-model="password_confirmation" type="password" id="password_confirmation" placeholder="Confirmez votre mot de passe" required />
      </div>
      <div class="form-group">
        <label for="image">Image:</label>
        <input @change="handleImageUpload" type="file" id="image" />
      </div>
      <button type="submit" class="submit-button">S'inscrire</button>
      <p v-if="error" class="error-message">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';

const router = useRouter();
const userStore = useUserStore();

const pseudo = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const image = ref(null);
const error = ref('');

async function signup() {
  try {
    await userStore.signup(email.value, password.value, pseudo.value, password_confirmation.value, image.value);
    router.push('/');
  } catch (err) {
    error.value = "Erreur lors de l'inscription. Vérifiez vos informations.";
  }
}

function handleImageUpload(event) {
  image.value = event.target.files[0];
}
</script>

<style scoped>
/* Ajouter du style si nécessaire */
</style>
