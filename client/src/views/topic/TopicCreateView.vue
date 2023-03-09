<template>
  <form @submit.prevent="submitForm">
    <div class="flex justify-center w-full pt-6">
      <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
        <div class="div-table-header">
          Neues Thema einreichen
        </div>
        <div class="div-table-content">
          <div class="mb-6">
            <label class="label" for="topic">Thema</label>
            <div v-if="validate">
              <div v-if="validate.topic" class="text-red-600">
                {{validate.topic.topic}}{{validate.topic.isEmpty}}{{validate.topic.stringLengthTooShort}}{{validate.topic.stringLengthTooLong}}
              </div>
            </div>
            <input id="topic" v-model="topic.topic" class="input" name="topic" placeholder="" type="text" minlength="3" maxlength="50" required/>
          </div>

          <div class="mb-6">
            <label class="label" for="description">Beschreibung</label>
            <div v-if="validate">
              <div v-if="validate.description" class="text-red-600">
                {{validate.description.isEmpty}}{{validate.description.stringLengthTooShort}}{{validate.description.stringLengthTooLong}}
              </div>
            </div>
            <textarea id="description" v-model="topic.description" class="textarea" name="description" rows="16" minlength="20" maxlength="8096" required></textarea>
          </div>

          <div class="flex justify-center">
            <button class="button" type="submit">Erstellen</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import {ref} from "vue";
import {useTopicStore} from "@/store/topic";
import {storeToRefs} from "pinia";
const topicStore = useTopicStore();
const {validate, error} = storeToRefs(topicStore);
import {useToast} from "vue-toastification";
import {useRouter} from 'vue-router';

const toast = useToast();
const router = useRouter();
const topic = ref({
  topic: '',
  description: ''
})

const submitForm = () => {
  topicStore.pushTopic(topic.value);
  if (error.value == null) {
    router.push({name: 'topics_list_available'});
    toast.info('Thema wurde eingereicht und wird geprüft');
  }
}

</script>

<style scoped>

</style>
