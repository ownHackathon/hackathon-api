import {useUserStore} from "@/store/user";
import axios from "axios";

export default function useUser() {
    const userStore = useUserStore();

    const loadUser = () => {
        if (userStore.user !== null) {
            return;
        }

        axios
            .get('/api/me')
            .then((response) => {
                if (response.data.id !== undefined) {
                    userStore.setUser(response.data);
                    userStore.isAuthenticated = true;
                }
            })
            .catch((err) => {
                userStore.user = null;
                userStore.isAuthenticated = false;
                console.error(err);
            });

    };

    const unLoadUser = () => {
        userStore.user = null;
        userStore.isAuthenticated = false;
    };

    return {
        loadUser,
        unLoadUser,
    };
}
