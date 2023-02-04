<template>
  <TheSpinner/>

  <div id="content" class="hidden">
    <EventEntryHeadData
        :title="event.title"
        :description="event.description"
        :fulltext="event.eventText"
    />

    <div class="py-3"></div>

    <EventEntryTopic
        :topic="event.topic"
        :eventSubscribeStatus="eventSubscribeStatus"
    />

    <div class="py-3"></div>

    <EventEntryData
        :startTime="event.startTime"
        :duration="event.duration"
        :status="event.status"
    />

    <div class="py-3"></div>

    <div v-show="isShowSubscribeButton">
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

    <GoBackButton/>

    <EventEntryOwner
        :createTime="event.createTime"
        :owner="event.owner"
    />
  </div>
</template>

<script setup>
import axios from "axios";
import {useRoute} from "vue-router";
import {computed, onMounted, ref} from "vue";
import {useUserStore} from "@/store/UserStore";
import useEventService from "@/composables/EventService";
import useUserService from "@/composables/UserService";
import EventEntryHeadData from "@/views/event/components/EventEntryHeadData";
import EventEntryTopic from "@/views/event/components/EventEntryTopic";
import EventEntryData from "@/views/event/components/EventEntryData";
import EventEntryOwner from "@/views/event/components/EventEntryOwner";
import EventEntryParticipantsList from "@/views/event/components/EventEntryParticipantsList";
import {useToast} from "vue-toastification";
import TheSpinner from "@/components/ui/TheSpinner";
import GoBackButton from "@/components/GoBackButton.vue";

const event = ref({});
const route = useRoute();
const toast = useToast();
const userStore = useUserStore();
const userService = useUserService();
const eventService = useEventService();
const eventSubscribeStatus = ref(0);

const isShowSubscribeButton = computed(() => {
  return userService.isAuthenticated() && eventService.canStillParticipate(event.value.status);
});

function sortByName(a, b) {
  return a.username.localeCompare(b.username);
}

function sortParticpantList() {
  if (eventService.hasParticipants(event.value.participants)) {
    event.value.participants.sort(sortByName);
  }
}

onMounted(async () => {
  if (route.params.eventName) {
    await axios
        .get(`/api/event/${route.params.eventName}`)
        .then(response => {
          route.params.id = response.data.eventId;
        });
  }
  await axios
      .get(`/api/event/${route.params.id}`)
      .then(response => {
        event.value = response.data;
        sortParticpantList();
        eventSubscribeStatus.value = +eventService.isUserInParticipantList(event.value.participants, userStore.user);
        document.getElementById('spinner').classList.add('hidden');
        document.getElementById('content').classList.remove('hidden');
      });
});

function addUserAsParticipantToEvent() {
  axios
      .put(`/api/event/participant/subscribe/${route.params.id}`)
      .then(async response => {
        let participant = await response.data;
        event.value.participants.push(participant);
        sortParticpantList();
        eventSubscribeStatus.value = 1;
        toast.success('Anmeldung zum Event erfolgreich');
      });
}

function removeUserAsParticipantFromEvent() {
  axios
      .put(`/api/event/participant/unsubscribe/${route.params.id}`)
      .then(async response => {
        await response.data;
        let participant = eventService.findUserInParticipantList(event.value.participants, userStore.user);
        event.value.participants.splice(event.value.participants.indexOf(participant), 1);
        sortParticpantList();
        eventSubscribeStatus.value = 0;
        toast.success('Abmeldung vom Event erfolgreich');
      });
}

</script>

<style lang="scss">
</style>
