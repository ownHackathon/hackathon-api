<template>
  <p class="py-6">Hier bekommt ihr eine Übersicht über alle derzeit laufenden und bereits abgeschlossenen Events</p>
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
            Startzeit
          </th>
          <th class="hidden lg:table-cell py-3" scope="col">
            Endzeit
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
          :key="entry.id"
          :id="entry.id"
          :owner="entry.owner"
          :eventname="entry.name"
          :description="entry.description"
          :duration="entry.duration"
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
