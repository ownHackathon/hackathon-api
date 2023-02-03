<template>
  <div v-if="loading">
    <TheSpinner/>
  </div>
  <div id="content">
    <div class="relative overflow-x-auto shadow-md rounded-lg mt-5">
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
</template>

<script setup>
import TheSpinner from "@/components/ui/TheSpinner.vue";
import TopicListEntry from "@/views/topic/componets/TopicListEntry.vue";
import {storeToRefs} from "pinia";
import {useTopicStore} from "@/store/topic";
import {onMounted} from "vue";

const topicStore = useTopicStore();
const {topics, loading} = storeToRefs(topicStore);


onMounted(() => {
      topicStore.fetchAvailableTopics();
    }
);
</script>

<style scoped>

</style>
