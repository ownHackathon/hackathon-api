import {defineStore} from "pinia";
import {ref} from 'vue';

export const useCsrfStore = defineStore("csrf", () => {
  const csrfToken = ref(null);

  return {csrfToken}
});
