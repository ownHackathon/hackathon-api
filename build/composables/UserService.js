import {useUserStore} from "@/store/UserStore";
import axios from "axios";
import router from "@/router";

export default function useUserService() {
  const user = useUserStore();

  const loadUser = () => {
    if (user.user !== null) {
      return;
    }

    axios
        .get('/api/me')
        .then((response) => {
          if (response.status === 200 && response.data.uuid !== undefined) {
            user.setUser(response.data);
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
    user.user = null;
    localStorage.removeItem('token');
    router.push('/');
  };

  const isAuthenticated = () => {
    return user.user !== null;
  };

  return {
    loadUser, unLoadUser, isAuthenticated,
  };
}
