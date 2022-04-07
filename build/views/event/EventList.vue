<template>
  <p class="py-6">Hier bekommt ihr eine Übersicht über alle derzeit laufenden und bereits abgeschlossenen Events</p>
  <div class="relative overflow-x-auto shadow-md rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th class="px-3 py-3" scope="col">
            Name
          </th>

          <th class="hidden md:flex py-3" scope="col">
            Beschreibung
          </th>

          <th class="py-3" scope="col">
            Startzeit
          </th>

          <th class="hidden lg:flex py-3" scope="col">
            Endzeit
          </th>

          <th class="py-3" scope="col">
            Status
          </th>

          <th class="hidden sm:flex py-3" scope="col">
            Ersteller
          </th>

        </tr>
      </thead>
      <tbody>
      <EventListEntry
          v-for="entry in data"
          :key="entry.id"
          :description="entry.description"
          :duration="entry.duration"
          :eventname="entry.name"
          :owner="entry.userId"
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
import {ref} from 'vue';

const data = ref(null);
axios
    .get("/event/list/")
    .then(async response => {
      data.value = await response.data;
    });
</script>
