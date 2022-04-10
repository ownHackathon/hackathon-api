import {defineStore} from "pinia";

export const useUserStore = defineStore("user", {
    state: () => {
        return {
            user: null,
            isAuthenticated: false,
        };
    },
    actions: {
        setUser(user) {
            this.user = user;
        }
    }
});
