<template>
  <div class="mt-5 mb-2">
    <button class="button" @click="goBack">
      zurück
    </button>
  </div>
  <div v-if="loading">
    <TheSpinner/>
  </div>
  <div v-if="error" class="center">
    <span>Es ist ein Fehler aufgetreten: {{error}}</span>
  </div>
  <div id="content">
    <div class="relative overflow-x-auto shadow-md rounded-lg">
      <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
        <tr>
          <th class="px-3 py-3" scope="col">
            Thema
          </th>
          <th class="xl:table-cell py-3" scope="col">
            Beschreibung
          </th>
        </tr>
        </thead>
        <tbody>
        <TopicListEntry
            v-for="data in topics"
            :key="data.uuid"
            :uuid="data.uuid"
            :topic="data.topic"
            :description="data.description"
        />
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-2 mb-2">
    <button class="button" @click="goBack">
      zurück
    </button>
  </div>
</template>

<script setup>
import TheSpinner from "@/components/ui/TheSpinner.vue";
import TopicListEntry from "@/views/topic/componets/TopicListEntry.vue";
import {storeToRefs} from "pinia";
import {useTopicStore} from "@/store/topic";
import {onMounted} from "vue";
import {useRouter} from "vue-router";

const topicStore = useTopicStore();
const {topics, loading, error} = storeToRefs(topicStore);
const router = useRouter();

onMounted(() => {
      topicStore.fetchAvailableTopics();
    }
);

function goBack() {
  return router.go(-1)
}
</script>

<style scoped>

</style>
