<template>
  <tr class="border-b bg-gray-800 border-gray-700">
    <th class="py-2 px-3 font-medium text-white whitespace-nowrap">
      <RouterLink :to="{name: 'home'}"> {{ eventname }}</RouterLink>
    </th>
    <td class="hidden md:table-cell py-2">{{ description }}</td>
    <td class="py-2" >{{ dateTime(startTime) }} Uhr</td>
    <td class="hidden lg:table-cell py-2">{{ dateTime(addTime(startTime, duration)) }} Uhr</td>
    <td class="hidden sm:table-cell py-2">{{ duration }} Tage</td>
    <td class="py-2">{{ statusTranslate(status) }}</td>
    <td class="hidden sm:table-cell py-2">{{ owner }}</td>
  </tr>
</template>

<script setup>
import { defineProps } from "vue";
import { dateTime, addTime } from "@/composables/moment.js";

defineProps({
  eventname: String,
  description: String,
  startTime: Date,
  duration: Int32Array,
  status: Intl,
  owner: Int32Array
})

const statusList = [
    'geschlossen',
    'bald',
    'vorbereiten',
    'läuft',
    'auswerten',
    'beendet',
]

const statusTranslate = (value) => {
  return statusList[value-1];
}

</script>
