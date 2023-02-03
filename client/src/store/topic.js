import {defineStore} from "pinia";
import axios from "axios";

export const useTopicStore = defineStore("topicStore", {
  state: () => ({
    topics: [], topic: 'Test', loading: false, error: null
  }), getters: {}, actions: {
    async fetchAvailableTopics() {
      this.loading = true;
      await axios
          .get('/api/topics/available')
          .then((response) => {
            this.topics = response.data.topics;
          })
          .catch((error) => {
            this.error = error;
          })
          .finally(() => {
            this.loading = false;
          });
    }
  }
});
