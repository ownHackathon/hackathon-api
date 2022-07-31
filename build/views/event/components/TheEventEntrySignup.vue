<template>
  <div v-show="userService.isAuthenticated() && canStillParticipate">
    <div v-if="!isUserInList" class="flex">
      <button class="button" @click="addAsParticipant(userStore.user.uuid)">Zum Event <span class="text-green-600">anmelden</span></button>
    </div>
    <div v-else>
      <button class="button">Vom Event <span class="text-red-600">abmelden</span></button>
    </div>
  </div>
</template>

<script setup>
import {defineProps, computed} from "vue";
import {useRoute} from "vue-router";
import {useUserStore} from "@/store/UserStore";
import useUserService from "@/composables/UserService";
import useEventService from "@/composables/EventService";

const route = useRoute();
const userService = useUserService();
const userStore = useUserStore();
const eventService = useEventService();

const data = defineProps({
  status: Intl,
  participants: Array,
})

const isUserInList = computed(() => {
  if (hasParticipants.value) {
    return data.participants.find(element => element.username === userStore.user.name) !== undefined;
  }

  return false;
});

const canStillParticipate = computed(() => {
  return data.status < 3;
});

function addAsParticipant(userUuid) {
  eventService.addUserAsParticipantToEvent(userUuid, route.params.id);
}

const hasParticipants = computed(() => {
  return (data.participants !== undefined && data.participants.length > 0);
});
</script>

<style lang="scss">

</style>
