<template>
  <div class="top-6 flex w-full justify-center items-center">
    <h1 class="font-bold">{{ data.name }}</h1>
  </div>

  <div class="p-3 flex w-full justify-center items-center">
    <p class="text-xs text-gray-400 ">
      {{ data.description }}
    </p>
  </div>

  <div class="relative overflow-x-auto shadow rounded">
    <div class="flex py-1 px-2 text-xs uppercase bg-gray-700 text-gray-400">
      <div class="grow">Beschreibung</div>
      <div class="flex-none">Erstellt: {{ date(data.createTime) }}</div>
    </div>
    <div class="py-1 px-2 text-gray-400 bg-gray-800">
      {{ data.eventText }}
    </div>
  </div>

  <div class="py-6"></div>

  <div class="relative overflow-x-auto shadow rounded">
    <div class="flex py-1 px-2 text-xs uppercase bg-gray-700 text-gray-400">
      <div>Thema: <span class="font-bold">{{ data.topic.title }}</span></div>
    </div>
    <div class="py-1 px-2 text-gray-400 bg-gray-800">
      {{ data.topic.description }}
    </div>
  </div>

  <div class="py-6"></div>

  <div class="relative overflow-x-auto shadow rounded">
    <div class="flex py-1 px-2 text-xs uppercase bg-gray-700 text-gray-400">
      Daten
    </div>
    <div class="flex border-t border-gray-700 flex py-1 px-2 text-gray-400 bg-gray-800">
      <div class="grow">Start:</div>
      <div class="flex-initial">{{ dateTime(data.startTime) }} Uhr</div>
    </div>
    <div class="flex border-t border-gray-700 py-1 px-2 text-gray-400 bg-gray-800">
      <div class="grow">Laufzeit:</div>
      <div class="flex-initial">{{ data.duration }} Tage</div>
    </div>
    <div class="flex border-t border-gray-700 py-1 px-2 text-gray-400 bg-gray-800">
      <div class="grow">Ende:</div>
      <div class="flex-initial">{{ dateTime(addTime(data.startTime, data.duration)) }} Uhr</div>
    </div>
    <div class="flex border-t border-gray-700 py-1 px-2 text-gray-400 bg-gray-800">
      <div class="grow">Status:</div>
      <div class="flex-initial">{{ getStatusText(data.status) }}</div>
    </div>
  </div>

  <div class="py-6"></div>

  <div class="relative overflow-x-auto shadow-md rounded-lg">
    <div class="flex py-1 px-2 text-xs uppercase bg-gray-700 text-gray-400">
      <div class="flex-1">Teilnehmer</div>
      <div class="flex-1">Projekt</div>
    </div>
    <div v-for="participant in data.participants" :key="participant.id"
         class="flex bg-gray-800 py-1 px-2 border-t border-gray-700"
    >
      <div class="flex-1">
        <RouterLink to="/user/">{{ participant.username }}</RouterLink>
      </div>
      <div v-if="participant.projectId" class="flex-1">
        <RouterLink to="/project/">{{ participant.projectTitle }}</RouterLink>
      </div>
      <div v-else class="flex-1">-</div>
    </div>
  </div>

  <div class="flex p-3 text-gray-800">
    <div class="grow">Event erstellt am: {{date(data.createTime)}}</div>
    <div class="flex-initial pl-2">von: <RouterLink to="/user/">{{ data.owner}}</RouterLink></div>
  </div>
</template>

<script setup>
import axios from "axios";
import {useRoute} from "vue-router";
import {ref} from "vue";
import {addTime, date, dateTime} from '@/composables/moment.js';
import { getStatusText } from "@/composables/status";

const route = useRoute();
const data = ref(null);

axios
    .get(`/event/${route.params.id}`)
    .then(async response => {
      data.value = await response.data;
    });
</script>

<style lang="scss">
h1 {
  font-size: 2rem;
  color: #e43c5c;
}
</style>
