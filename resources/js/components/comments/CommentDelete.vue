<template>
    <div class="comment-delete">
        <p>Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
        <button class="button-confirm" @click="deleteComment">Supprimer</button>
        <button class="button-cancel" @click="cancelDelete">Annuler</button>
    </div>
</template>

<script>
import axios from './../../axios';

export default {
    props: {
        commentId: {
            type: Number,
            required: true,
        },
    },
    methods: {
        async deleteComment() {
            try {
                await axios.delete(`/comments/${this.commentId}`);
                this.$emit('comment-deleted'); 
            } catch (error) {
                console.error('Erreur lors de la suppression du commentaire:', error);
            }
        },
        cancelDelete() {
            this.$emit('cancel-delete'); 
        },
    },
};
</script>

<style scoped>
.comment-delete {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

button {
    margin-right: 10px;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
}

.button-confirm {
    background-color: #46c028;
    color: white;
}

.button-confirm:hover {
    background-color: #31a514;
}

.button-cancel {
    background-color: rgb(177, 21, 21);
    color: white;
}

.button-cancel:hover {
    background-color: rgb(122, 12, 12);

}
</style>