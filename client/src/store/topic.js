import {defineStore} from "pinia";
import axios from "axios";

export const useTopicStore = defineStore("topicStore", {
  state: () => ({
    topics: [],
    topic: 'Test',
    loading: false,
    validate: null,
    error: null
  }),
  getters: {},
  actions: {
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
    },

    async pushTopic(topic) {
      this.loading = true;
      await axios
          .post('/api/topic', topic,)
          .then((response) => {
              this.topic = response.data;
              this.validate = null
              this.error = null
          })
          .catch((error) => {
            if (error.response.status === 400) {
              this.validate = error.response.data;
            }
            this.error = error;
          })
          .finally(() => {
            this.loading = false;
          })
    }
  }
});
