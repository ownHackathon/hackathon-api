<template>
  <div class="top-6 flex w-full justify-center items-center">
    <h1 class="font-bold">{{ data.name }}</h1>
  </div>

  <div class="p-3 flex w-full justify-center items-center">
    <p class="text-xs text-gray-400 ">
      {{ data.description }}
    </p>
  </div>

  <div class="div-table">
    <div class="div-table-header">
      <div class="grow">Beschreibung</div>
    </div>
    <div class="div-table-content">
      <div class="prose max-w-max text-gray-400">
        <Markdown :source="data.eventText"/>
      </div>
    </div>
  </div>

  <div class="py-6"></div>

  <div v-if="isShowTopic">
    <div class="div-table">
      <div class="div-table-header">
        <div>Thema: <span class="font-bold">{{ data.topic.title }}</span></div>
      </div>
      <div class="div-table-content">
        <div class="prose max-w-max text-gray-400">
          <Markdown :source="data.topic.description"/>
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    <div class="div-table">
      <div class="div-table-header">
        <div>Thema:</div>
      </div>
      <div v-if="user.isAuthenticated()">
        <div class="div-table-content">
          <span class="flex justify-center pb-6">Noch kein Thema? Na dann mal fix zur...</span>
          <div class="flex justify-center">
            <button class="button">Themenauswahl</button>
          </div>
        </div>
      </div>
      <div v-else>
        <div class="div-table-content">
          <span class="flex justify-center pb-6">Themenauswahl hat noch nicht statt gefunden.</span>
          <div class="div-table-content">
            Für das Themenvoting sind nur
            <RouterLink :to="{name: 'register'}">registrierte</RouterLink>
            und
            <RouterLink :to="{name: 'login'}">angemeldete</RouterLink>
            sowie teilnehmende Benutzer zugelassen.
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="py-6"></div>

  <div class="div-table">
    <div class="div-table-header">
      Daten
    </div>
    <div class="div-table-content-row">
      <div class="grow">Start:</div>
      <div class="flex-initial">{{ dateTime(data.startTime) }} Uhr</div>
    </div>
    <div class="div-table-content-row">
      <div class="grow">Laufzeit:</div>
      <div class="flex-initial">{{ data.duration }} Tage</div>
    </div>
    <div class="div-table-content-row">
      <div class="grow">Ende:</div>
      <div class="flex-initial">{{ dateTime(addTime(data.startTime, data.duration)) }} Uhr</div>
    </div>
    <div class="div-table-content-row">
      <div class="grow">Status:</div>
      <div class="flex-initial">{{ getStatusText(data.status) }}</div>
    </div>
  </div>

  <div class="py-6"></div>

  <div class="div-table">
    <div class="div-table-header">
      <div class="flex-1">Teilnehmer</div>
      <div class="flex-1">Projekt</div>
    </div>


    <div v-if="user.isAuthenticated()">
      <div v-show="canStillParticipate">
        <div v-show="!isUserInList">
          Anmeldebutton
        </div>
      </div>
      <div v-if="haveParticipants">
        <div v-for="participant in data.participants" :key="participant.id" class="div-table-content-row flex bg-gray-800 py-1 px-2 border-t border-gray-700">
          <div class="flex-1">
            <RouterLink to="/user/">{{ participant.username }}</RouterLink>
          </div>
          <div v-if="participant.projectId" class="flex-1">
            <RouterLink to="/project/">{{ participant.projectTitle }}</RouterLink>
          </div>
          <div v-else class="flex-1">-</div>
        </div>
      </div>
      <div v-else>
        <div class="div-table-content">
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
  <div class="py-6"></div>

  <div class="flex p-3 text-gray-800">
    <div class="grow">Event erstellt am: {{ date(data.createTime) }}</div>
    <div class="flex-initial pl-2">von:
      <RouterLink to="/user/">{{ data.owner }}</RouterLink>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import {useRoute} from "vue-router";
import useUser from "@/composables/user";
import {computed, ref} from "vue";
import {addTime, date, dateTime} from '@/composables/moment.js';
import {getStatusText} from "@/composables/status";
import Markdown from 'vue3-markdown-it';

const data = ref({});
const route = useRoute();
const user = useUser();

axios
    .get(`/event/${route.params.id}`)
    .then(async response => {
      data.value = await response.data;
    });

const isShowTopic = computed(() => {
  return (Object.prototype.toString.call(data.value.topic) === "[object Object]");
});

const haveParticipants = computed(() => {
  return (data.value.participants !== undefined && data.value.participants.length > 0);
});

const isUserInList = computed(() => {
  let found = undefined;
  if (data.value.participants !== undefined) {
    found = data.value.participants.find(element => element.username === 'BibaltiK');
    console.log(found);
  }
  return found !== undefined;
});

const canStillParticipate = computed(() => {
  return data.value.status < 3;
});
</script>

<style lang="scss">
h1 {
  font-size: 2rem;
  color: #e43c5c;
}
</style>
