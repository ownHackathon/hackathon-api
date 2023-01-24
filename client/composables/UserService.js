import {useUserStore} from "@/store/UserStore";
import axios from "axios";

export default function useUserService() {
  const user = useUserStore();

  const loadUser = async () => {
    if (user.user !== null) {
      return;
    }

    await axios
        .get('/api/me')
        .then((response) => {
          if (response.status === 200 && response.data.uuid !== undefined) {
            user.setUser(response.data);
          } else if (response.status === 401) {
            unLoadUser();
          }
        })
        .catch(() => {
          unLoadUser();
        });
  };

  const unLoadUser = () => {
    user.user = null;
    localStorage.removeItem('token');
  };

  const isAuthenticated = () => {
    return user.user !== null;
  };

  return {
    loadUser, unLoadUser, isAuthenticated,
  };
}
