<template>
    <div>
      <h1>Déconnexion</h1>
      <button @click="logout">Se déconnecter</button>
    </div>
  </template>
  
  <script setup>
  import { useRouter } from 'vue-router';
  import { useUserStore } from '../../stores/user';
  
  const router = useRouter();
  const userStore = useUserStore();
  
  async function logout() {
    try {
      await userStore.logout();
      router.push('/');
    } catch (err) {
      console.error('Erreur lors de la déconnexion:', err);
    }
  }
  </script>
  