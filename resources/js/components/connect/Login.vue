<template>
  <div class="login-container">
    <h1>Connexion</h1>
    <form @submit.prevent="login" class="login-form">
      <div class="form-group">
        <label for="email">Email:</label>
        <input v-model="email" type="email" id="email" placeholder="Entrez votre email" required />
      </div>
      <div class="form-group">
        <label for="password">Mot de passe:</label>
        <input v-model="password" type="password" id="password" placeholder="Entrez votre mot de passe" required />
      </div>
      <div class="form-group">
        <label for="remember">Se souvenir de moi:</label>
        <input v-model="remember" type="checkbox" id="remember" />
      </div>
      <button type="submit" class="submit-button">Se connecter</button>
      <p v-if="error" class="error-message">{{ error }}</p>
      <p>
        Vous n'avez pas de compte ? <a href="#" @click="switchMode">S'inscrire</a>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';

const router = useRouter();
const userStore = useUserStore();
const emit = defineEmits(['switch-mode']);

const email = ref('');
const password = ref('');
const remember = ref(false);
const error = ref('');

async function login() {
  try {
    await userStore.login(email.value, password.value, remember.value);
    router.push('/');
  } catch (err) {
    error.value = 'Erreur lors de la connexion. Vérifiez vos informations.';
  }
}

function switchMode() {
  emit('switch-mode');
}
</script>

<style scoped>
/* Ajouter du style si nécessaire */
</style>
