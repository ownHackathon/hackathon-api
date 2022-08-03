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
    <button v-if="eventSubscribeStatus===0" class="button" @click="addUserAsParticipantToEvent()">
      Zum Event <span class="text-green-600">anmelden</span>
    </button>
    <button v-else class="button" @click="removeUserAsParticipantFromEvent()">
      Vom Event <span class="text-red-600">abmelden</span>
    </button>
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
import useEventService from "@/composables/EventService";
import useUserService from "@/composables/UserService";
import EventEntryHeadData from "@/views/event/components/EventEntryHeadData";
import EventEntryTopic from "@/views/event/components/EventEntryTopic";
import EventEntryData from "@/views/event/components/EventEntryData";
import EventEntryOwner from "@/views/event/components/EventEntryOwner";
import EventEntryParticipantsList from "@/views/event/components/EventEntryParticipantsList";


const event = ref({});
const route = useRoute();
const userStore = useUserStore();
const userService = useUserService();
const eventService = useEventService();
const eventSubscribeStatus = ref(0);

function sortByName(a, b) {
  return a.username.localeCompare(b.username);
}

function sortParticpantList() {
  event.value.participants.sort(sortByName);
}

onMounted(() => {
  axios
      .get(`/event/${route.params.id}`)
      .then(async response => {
        event.value = await response.data;
        sortParticpantList();
        if (eventService.isUserInParticipantList(event.value.participants, userStore.user)) {
          eventSubscribeStatus.value = 1;
        }

      });
});

function addUserAsParticipantToEvent() {
  axios
      .put(`/event/participant/subscribe/${route.params.id}`)
      .then(async response => {
        let participant = await response.data;
        event.value.participants.push(participant);
        sortParticpantList();
        eventSubscribeStatus.value = 1;
      });
}

function removeUserAsParticipantFromEvent() {
  axios
      .put(`/event/participant/unsubscribe/${route.params.id}`)
      .then(async response => {
        await response.data;
        let participant = eventService.findUserInParticipantList(event.value.participants, userStore.user);
        event.value.participants.splice(event.value.participants.indexOf(participant), 1);
        sortParticpantList();
        eventSubscribeStatus.value = 0;
      });
}

</script>

<style lang="scss">
</style>
