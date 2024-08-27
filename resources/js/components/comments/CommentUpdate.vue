<template>
    <div class="comment-update">
        <h3>Modifier le commentaire</h3>
        <form @submit.prevent="updateComment">
            <textarea v-model="localComment.content" required></textarea>
            <button class="button-confirm" type="submit">Sauvegarder</button>
            <button class="button-cancel" @click="cancelEdit" type="button">Annuler</button>
        </form>
    </div>
</template>

<script>
import axios from './../../axios';

export default {
    props: {
        commentData: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            localComment: { ...this.commentData },
        };
    },
    methods: {
        async updateComment() {
            try {
                await axios.put(`/comments/${this.localComment.id}`, this.localComment);
                this.$emit('comment-updated', this.localComment); 
            } catch (error) {
                console.error('Erreur lors de la mise à jour du commentaire:', error);
            }
        },
        cancelEdit() {
            this.$emit('cancel-edit');
        },
    },
};
</script>

<style scoped>
.comment-update {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

textarea {
    width: 100%;
    height: 100px;
    margin-bottom: 10px;
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