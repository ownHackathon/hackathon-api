<template>
  <TheEventEntryMetaData
    :title="data.name"
    :description="data.description"
    :fulltext="data.eventText"
  />

  <TheEventThema
    :topic="data.topic"
  />

  <TheEventEntryData
    :startTime="data.startTime"
    :duration="data.duration"
    :status="data.status"
  />

  <TheEventEntrySignup
    :status="data.status"
    :participants="data.participants"
  />

  <TheEventEntryParticipantsList
    :participants="data.participants"
  />

  <TheEventEntryOwner
    :createTime="data.createTime"
    :owner="data.owner"
  />
</template>

<script setup>
import axios from "axios";
import {useRoute} from "vue-router";

import {ref} from "vue";
import TheEventEntryMetaData from "@/views/event/components/TheEventEntryMetaData";
import TheEventThema from "@/views/event/components/TheEventThema";
import TheEventEntryData from "@/views/event/components/TheEventEntryData";
import TheEventEntrySignup from "@/views/event/components/TheEventEntrySignup";
import TheEventEntryParticipantsList from "@/views/event/components/TheEventEntryParticipantsList";
import TheEventEntryOwner from "@/views/event/components/TheEventEntryOwner";

const data = ref({});
const route = useRoute();

axios
    .get(`/event/${route.params.id}`)
    .then(async response => {
      data.value = await response.data;
    });
</script>

<style lang="scss">
</style>
