import { defineStore } from "pinia";
import axios from "../axios";

export const useUserStore = defineStore("user", {
    state: () => ({
        user: null,
        token: localStorage.getItem("token") || null,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        $reset() {
            this.user = null;
        },
        setToken(token) {
            this.token = token;
            localStorage.setItem("token", token);
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        },
        async login(email, password, remember) {
            try {
                const response = await axios.post("/login", {
                    email,
                    password,
                    remember,
                });
                const { user, token } = response.data;
                console.log(token);
                if (user && token) {
                    this.setUser(user);
                    this.setToken(token);
                } else {
                    throw new Error("Échec de la connexion.");
                }
            } catch (error) {
                console.error("Erreur lors de la connexion:", error);
                throw error;
            }
        },

        async signup(email, password, pseudo, password_confirmation, image) {
            try {
                const formData = new FormData();
                formData.append("email", email);
                formData.append("password", password);
                formData.append("pseudo", pseudo);
                formData.append("password_confirmation", password_confirmation);
                if (image) formData.append("image", image);

                const response = await axios.post("/signup", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                const { user, token } = response.data;
                console.log("Signup Response:", response.data); // Debug
                if (user && token) {
                    this.setUser(user);
                    this.setToken(token);
                } else {
                    throw new Error("Échec de l'inscription.");
                }
            } catch (error) {
                console.error("Erreur lors de l'inscription:", error);
                throw error;
            }
        },

        async logout() {
            try {
                await axios.post("/logout");
                this.setUser(null);
                this.setToken(null);
                localStorage.removeItem("token");
                delete axios.defaults.headers.common["Authorization"];
            } catch (error) {
                console.error("Erreur lors de la déconnexion:", error);
            }
        },
        async fetchUserProfile() {
            if (this.token) {
                try {
                    const response = await axios.get("/user");
                    this.setUser(response.data);
                } catch (error) {
                    console.error(
                        "Erreur lors de la récupération des informations du profil:",
                        error
                    );
                }
            }
        },
    },
    persist: true,
});
