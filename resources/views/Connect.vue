<!-- resources/js/views/Connect.vue -->
<template>
  <div class="connect-container">
    <div v-if="isLoggedIn">
      <Logout />
    </div>
    <div v-else>
      <div v-if="isSignUpMode">
        <Signin @switch-mode="toggleSignUpMode" />
      </div>
      <div v-else>
        <Login @switch-mode="toggleSignUpMode" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useUserStore } from '../js/stores/user';
import Login from '../js/components/connect/Login.vue';
import Logout from '../js/components/connect/Logout.vue';
import Signin from '../js/components/connect/Signin.vue';

const userStore = useUserStore();
const isLoggedIn = computed(() => !!userStore.token);
const isSignUpMode = ref(false);

function toggleSignUpMode() {
  isSignUpMode.value = !isSignUpMode.value;
}
</script>

<style scoped>
.connect-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 90vh;
  background-color: #f4f4f4;
  padding: 20px;
}
</style>
