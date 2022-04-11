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
          if (response.data.uuid !== undefined) {
            userStore.setUser(response.data);
          }
        })
        .catch((err) => {
          unLoadUser();
          console.error(err);
        });
  };

  const unLoadUser = () => {
    userStore.user = null;
  };

  const isAuthenticated = () => {
    return userStore.user !== null;
  };

  return {
    loadUser, unLoadUser, isAuthenticated,
  };
}
