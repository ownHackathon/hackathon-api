<template>
  <p class="py-6">Hier bekommt ihr eine Übersicht über alle derzeit laufenden und bereits abgeschlossenen Events</p>
  <div v-if="user.isAuthenticated()">
    <div>
      <button class="button" @click="router.push({name: 'event_create'})">
        Event erstellen
      </button>
    </div>
  </div>
  <div class="relative overflow-x-auto shadow-md rounded-lg">
    <table class="w-full text-sm text-left text-gray-400">
      <thead class="text-xs uppercase bg-gray-700 text-gray-400">
      <tr>
        <th class="px-3 py-3" scope="col">
          Name
        </th>
        <th class="hidden xl:table-cell py-3" scope="col">
          Beschreibung
        </th>
        <th class="py-3" scope="col">
          Start
        </th>
        <th class="hidden lg:table-cell py-3" scope="col">
          Ende
        </th>
        <th class="hidden sm:table-cell py-3" scope="col">
          Laufzeit
        </th>
        <th class="py-3" scope="col">
          Status
        </th>
        <th class="hidden md:table-cell py-3" scope="col">
          Ersteller
        </th>
      </tr>
      </thead>
      <tbody>
      <EventListEntry
          v-for="entry in data"
          :id="entry.id"
          :key="entry.id"
          :description="entry.description"
          :duration="entry.duration"
          :eventname="entry.name"
          :owner="entry.owner"
          :start-time="entry.startTime"
          :status="entry.status"
      />
      </tbody>
    </table>
  </div>
</template>

<script setup>
import axios from "axios";
import EventListEntry from "@/views/event/components/EventListEntry";
import useUser from "@/composables/user";
import {useRouter} from "vue-router";
import {onMounted, ref} from 'vue';

const user = useUser();
const router = useRouter();
const data = ref(' ');
onMounted(() => {
  axios
      .get("/event/list/")
      .then(async response => {
        data.value = await response.data;
      });
});
</script>
