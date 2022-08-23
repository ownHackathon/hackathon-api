import {useCsrfStore} from "@/store/CsrfStore";
import axios from 'axios';
import router from "@/router";

const token = useCsrfStore();

export default function useCsrf() {

  const loadCsrf = () => {
    if (token.csrfToken !== null) {
      return;
    }

    axios
        .get('/api/csrf')
        .then((response) => {
          if (response.status === 200) {
            token.csrfToken.value = response.data.token;
          } else if (response.status === 401) {
            token.csrfToken = null;
            router.push("/error");
          }
        })
        .catch(() => {
          token.csrfToken = null;
          router.push("/error");
        });
  };

  return {
    loadCsrf
  };
}
