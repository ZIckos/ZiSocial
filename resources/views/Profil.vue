<template>
  <div class="profile-container">
    <div v-if="userStore.user">
      <h1>Profil de {{ userStore.user.pseudo }}</h1>
      <div class="profile-details">

        <!-- Email -->
        <div class="profile-item">
          <div v-if="editField !== 'email'">
            <p><strong>Email :</strong> {{ userStore.user.email }}</p>
            <button @click="startEditing('email')" class="edit-button">Modifier</button>
          </div>

          <!-- Modif Email -->
          <div v-else>
            <input v-model="form.email" id="email" type="email" placeholder="Modifier l'email" />
            <button @click="saveChanges('email')" class="save-button">Sauvegarder</button>
            <button @click="cancelEditing" class="cancel-button">Annuler</button>
          </div>
        </div>

        <!-- Pseudo -->
        <div class="profile-item">
          <div v-if="editField !== 'pseudo'">
            <p><strong>Pseudo :</strong> {{ userStore.user.pseudo }}</p>
            <button @click="startEditing('pseudo')" class="edit-button">Modifier</button>
          </div>

          <!-- Modif Pseudo -->
          <div v-else>
            <input v-model="form.pseudo" type="text" placeholder="Modifier le pseudo" />
            <button @click="saveChanges('pseudo')" class="save-button">Sauvegarder</button>
            <button @click="cancelEditing" class="cancel-button">Annuler</button>
          </div>
        </div>

        <button class="btn btn-danger" @click="deleteAccount">Supprimer son compte</button>
      </div>
    </div>
    <div v-else>
      <p>Chargement des informations du profil...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '../js/stores/user';
import axios from '../js/axios';

const userStore = useUserStore();
const router = useRouter();
const editField = ref(null);

const form = reactive({
  email: '',
  pseudo: '',
});

const startEditing = (field) => {
  editField.value = field;
};

const cancelEditing = () => {
  editField.value = null;
};

const saveChanges = async (field) => {
  try {
    const data = { [field]: form[field] };
    const response = await axios.put(`/users/${userStore.user.id}`, data);
    editField.value = null;
    userStore.setUser(response.data.user);
    alert(response.data.message)
    router.go(0);
  } catch (error) {
    console.error(`Erreur lors de la mise à jour du ${field}:`, error);
  }
};

const deleteAccount = () => {
  axios.delete(`/users/${userStore.user.id}`)
    .then(response => {
      alert(response.data.message)
      console.log(userStore.$reset())
      userStore.$reset();
      router.push('/');
    })
    .catch(error => {
      alert(error.message)
    })
}

</script>

<style scoped>
.profile-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: #f9f9f9;
  padding: 20px;
}

.profile-container h1 {
  margin-bottom: 20px;
  color: #333;
  text-align: center;
}

.profile-details {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 100%;
  max-width: 600px;
}

.profile-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 15px;
}

.field-display,
.field-edit {
  display: flex;
  align-items: center;
  width: 100%;
  justify-content: space-between;
}

.field-display p {
  margin: 0;
  font-size: 16px;
  flex: 1;
  display: flex;
  align-items: center;
}

.buttons {
  display: flex;
  gap: 10px;
}

.edit-button,
.save-button,
.cancel-button {
  background-color: #007bff;
  border: none;
  border-radius: 4px;
  color: white;
  font-size: 14px;
  padding: 5px 10px;
  cursor: pointer;
  transition: background-color 0.3s;
  display: flex;
  align-items: center;
}

.edit-button:hover,
.save-button:hover {
  background-color: #0056b3;
}

.cancel-button {
  background-color: #dc3545;
}

.cancel-button:hover {
  background-color: #c82333;
}

input[type="text"],
input[type="email"] {
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
  flex: 1;
}
</style>
