import {useUserStore} from "@/store/user";
import axios from "axios";
import router from "@/router";

export default function useUser() {
  const userStore = useUserStore();

  const loadUser = () => {
    if (userStore.user !== null) {
      return;
    }

    axios
        .get('/api/me')
        .then((response) => {
          if (response.status === 200 && response.data.uuid !== undefined) {
            userStore.setUser(response.data);
          } else if (response.status === 401) {
            unLoadUser();
          }
        })
        .catch((err) => {
          unLoadUser();
          console.error(err);
        });
  };

  const unLoadUser = () => {
    userStore.user = null;
    localStorage.removeItem('token');
    router.push('/');
  };

  const isAuthenticated = () => {
    return userStore.user !== null;
  };

  return {
    loadUser, unLoadUser, isAuthenticated,
  };
}
