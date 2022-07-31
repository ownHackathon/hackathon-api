<template>
  <TheEventEntryMetaData
    :title="data.name"
    :description="data.description"
    :fulltext="data.eventText"
  />
  <div class="py-6"></div>
  <TheEventEntryThema
    :topic="data.topic"
  />
  <div class="py-6"></div>
  <TheEventEntryData
    :startTime="data.startTime"
    :duration="data.duration"
    :status="data.status"
  />
  <div class="py-6"></div>
  <TheEventEntrySignup
    :status="data.status"
    :participants="data.participants"
  />

  <TheEventEntryParticipantsList
    :participants="data.participants"
  />
  <div class="py-6"></div>
  <TheEventEntryOwner
    :createTime="data.createTime"
    :owner="data.owner"
  />
</template>

<script setup>
import axios from "axios";
import {useRoute} from "vue-router";

import {onMounted, ref} from "vue";
import TheEventEntryMetaData from "@/views/event/components/TheEventEntryMetaData";
import TheEventEntryThema from "@/views/event/components/TheEventEntryThema";
import TheEventEntryData from "@/views/event/components/TheEventEntryData";
import TheEventEntrySignup from "@/views/event/components/TheEventEntrySignup";
import TheEventEntryParticipantsList from "@/views/event/components/TheEventEntryParticipantsList";
import TheEventEntryOwner from "@/views/event/components/TheEventEntryOwner";

const data = ref({});
const route = useRoute();

onMounted(() => {
  axios
      .get(`/event/${route.params.id}`)
      .then(async response => {
        data.value = await response.data;
      });
});
</script>

<style lang="scss">
</style>
