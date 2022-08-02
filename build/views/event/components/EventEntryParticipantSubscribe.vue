<template>
  <div v-show="userService.isAuthenticated() && eventService.canStillParticipate(data.status)">
    <div v-if="!eventService.isUserInParticipantList(data.participants, userStore.user)" class="flex">
      <button class="button" @click="addUserAsParticipantToEvent()">Zum Event <span class="text-green-600">anmelden</span></button>
    </div>
    <div v-else>
      <button class="button">Vom Event <span class="text-red-600">abmelden</span></button>
    </div>
  </div>
</template>

<script setup>
import {defineProps} from "vue";
//import {useRoute} from "vue-router";
import {useUserStore} from "@/store/UserStore";
import useUserService from "@/composables/UserService";
import useEventService from "@/composables/EventService";

//const route = useRoute();
const userService = useUserService();
const userStore = useUserStore();
const eventService = useEventService();

const data = defineProps({
  status: Intl,
  participants: Array,
})

const addUserAsParticipantToEvent = (() => {
  // eventService.addUserAsParticipantToEvent(userStore.user.uuid, route.params.id)

});
</script>

<style lang="scss">

</style>
