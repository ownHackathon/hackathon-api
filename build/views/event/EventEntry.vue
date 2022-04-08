<template>
  <div class="top-6 flex w-full justify-center items-center">
    <h1 class="font-bold">{{ data.name }}</h1>
  </div>

  <div class="p-3 flex w-full justify-center items-center">
    <p class="text-xs text-gray-400 ">
      {{ data.description }}
    </p>
  </div>

  <DivTable>
    <DivTableHeader>
      <div class="grow">Beschreibung</div>
      <div class="flex-none">Erstellt: {{ date(data.createTime) }}</div>
    </DivTableHeader>
    <DivTableContent>
      {{ data.eventText }}
    </DivTableContent>
  </DivTable>

  <DivTable>
    <DivTableHeader>
      <div>Thema: <span class="font-bold">{{ data.topic.title }}</span></div>
    </DivTableHeader>
    <DivTableContent v-if="isShowTopic">
      {{ data.topic.description }}
    </DivTableContent>
    <DivTableContent v-else >
      <span class="flex justify-center pb-6">Noch kein Thema? Na dann mal fix zur...</span>
      <TButton>Themenauswahl</TButton>
    </DivTableContent>
  </DivTable>

  <DivTable>
    <DivTableHeader>
      Daten
    </DivTableHeader>
    <DivTableContentRow>
      <div class="grow">Start:</div>
      <div class="flex-initial">{{ dateTime(data.startTime) }} Uhr</div>
    </DivTableContentRow>
    <DivTableContentRow>
      <div class="grow">Laufzeit:</div>
      <div class="flex-initial">{{ data.duration }} Tage</div>
    </DivTableContentRow>
    <DivTableContentRow>
      <div class="grow">Ende:</div>
      <div class="flex-initial">{{ dateTime(addTime(data.startTime, data.duration)) }} Uhr</div>
    </DivTableContentRow>
    <DivTableContentRow>
      <div class="grow">Status:</div>
      <div class="flex-initial">{{ getStatusText(data.status) }}</div>
    </DivTableContentRow>
  </DivTable>

  <DivTable>
    <DivTableHeader>
      <div class="flex-1">Teilnehmer</div>
      <div class="flex-1">Projekt</div>
    </DivTableHeader>
    <DivTableContentRow v-for="participant in data.participants" :key="participant.id"
         class="flex bg-gray-800 py-1 px-2 border-t border-gray-700"
    >
      <div class="flex-1">
        <RouterLink to="/user/">{{ participant.username }}</RouterLink>
      </div>
      <div v-if="participant.projectId" class="flex-1">
        <RouterLink to="/project/">{{ participant.projectTitle }}</RouterLink>
      </div>
      <div v-else class="flex-1">-</div>
    </DivTableContentRow>
  </DivTable>

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
import {computed, onMounted, ref} from "vue";
import {addTime, date, dateTime} from '@/composables/moment.js';
import {getStatusText} from "@/composables/status";
import DivTable from "@/components/form/DivTable";
import DivTableHeader from "@/components/form/DivTableHeader";
import DivTableContent from "@/components/form/DivTableContent";
import DivTableContentRow from "@/components/form/DivTableContentRow";
import TButton from "@/components/form/TButton";

const route = useRoute();
const data = ref(' ');

const isShowTopic = computed(() => {
          return (Object.prototype.toString.call(data.value.topic) === "[object Object]" );
    });

onMounted(() => {
  axios
      .get(`/event/${route.params.id}`)
      .then(async response => {
        data.value = await response.data;
      });
});
</script>

<style lang="scss">
h1 {
  font-size: 2rem;
  color: #e43c5c;
}
</style>
