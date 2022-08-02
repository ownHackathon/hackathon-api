<template>
  <EventEntryHeadData
      :title="event.title"
      :description="event.description"
      :fulltext="event.eventText"
  />

  <div class="py-3"></div>

  <EventEntryTopic
      :topic="event.topic"
  />

  <div class="py-3"></div>

  <EventEntryData
      :startTime="event.startTime"
      :duration="event.duration"
      :status="event.status"
  />

  <div class="py-3"></div>

  <div v-show="userService.isAuthenticated() && eventService.canStillParticipate(event.status)">
    <div v-if="!eventService.isUserInParticipantList(event.participants, userStore.user)" class="flex">
      <button class="button" @click="addUserAsParticipantToEvent()">Zum Event <span class="text-green-600">anmelden</span></button>
    </div>
    <div v-else>
      <button class="button">Vom Event <span class="text-red-600">abmelden</span></button>
    </div>
  </div>

  <EventEntryParticipantsList
      :participants="event.participants"
  />

  <EventEntryOwner
      :createTime="event.createTime"
      :owner="event.owner"
  />
</template>

<script setup>
import axios from "axios";
import {useRoute} from "vue-router";
import {onMounted, ref} from "vue";
import {useUserStore} from "@/store/UserStore";
import useUserService from "@/composables/UserService";
import useEventService from "@/composables/EventService";
import EventEntryHeadData from "@/views/event/components/EventEntryHeadData";
import EventEntryTopic from "@/views/event/components/EventEntryTopic";
import EventEntryData from "@/views/event/components/EventEntryData";
import EventEntryParticipantsList from "@/views/event/components/EventEntryParticipantsList";
import EventEntryOwner from "@/views/event/components/EventEntryOwner";

const event = ref({});
const route = useRoute();
const userService = useUserService();
const userStore = useUserStore();
const eventService = useEventService();

onMounted(() => {
  axios
      .get(`/event/${route.params.id}`)
      .then(async response => {
        event.value = await response.data;
      });

});

const addUserAsParticipantToEvent = (() => {
  const participant = eventService.addUserAsParticipantToEvent(route.params.id);
  event.value.participants.push(participant);
});

</script>

<style lang="scss">
</style>
