<template>
  <div class="div-table">
    <div class="div-table-header">
      <div class="flex-1">Teilnehmer</div>
      <div class="flex-1">Projekt</div>
    </div>

    <div v-if="userService.isAuthenticated()">
      <div v-if="eventService.hasParticipants(data.participants)">
        <div v-for="participant in data.participants" :key="participant.id" class="div-table-content-row flex bg-gray-800 py-1 px-2 border-t border-gray-700">
          <div class="flex-1">
            <!--<RouterLink :to="{name: 'user_entry', params: { uuid: participant.userUuid }}">{{ participant.username }}</RouterLink>-->
            <RouterLink to="/">{{ participant.username }}</RouterLink>
          </div>
          <div v-if="participant.projectId" class="flex-1">
            <RouterLink to="/project/">{{ participant.projectTitle }}</RouterLink>
          </div>
          <div v-else class="flex-1">-</div>
        </div>
      </div>
      <div v-else>
        <div id="no-participants" class="div-table-content">
          Es hat sich bisher noch kein Benutzer zur Teilnahme angemeldet.
        </div>
      </div>
    </div>

    <div v-else>
      <div class="div-table-content">
        Zum Anmelden an ein Event und einsehen der Daten bitte
        <RouterLink :to="{name: 'register'}">registrieren</RouterLink>
        und
        <RouterLink :to="{name: 'login'}">angemelden</RouterLink>
        .
      </div>
    </div>
  </div>
</template>

<script setup>
import useUserService from "@/composables/UserService";
import useEventService from "@/composables/EventService";
import {defineProps,  onUpdated, ref} from "vue";
const userService = useUserService();
const eventService = useEventService();
const props = defineProps({
  participants: Array,
});
const data = ref(props);

onUpdated(()=>{
  console.log(data);
});

</script>

<style lang="scss">

</style>
