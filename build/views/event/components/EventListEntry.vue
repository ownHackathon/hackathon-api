<template>
    <tr v-if="status!== 7" class="border-b bg-gray-800 border-gray-700">
      <th class="py-2 px-3 font-medium text-white whitespace-nowrap">
        <RouterLink :to="{ name: 'event_entry', params: { id: id }}"> {{ eventname }}</RouterLink>
      </th>
      <td class="hidden xl:table-cell py-2">{{ description }}</td>
      <td class="py-2">{{ dateTime(startTime) }} Uhr</td>
      <td class="hidden lg:table-cell py-2">{{ dateTime(addTime(startTime, duration)) }} Uhr</td>
      <td class="hidden sm:table-cell py-2">{{ duration }} Tage</td>
      <td class="py-2">{{ getStatusText(status) }}</td>
      <td class="hidden md:table-cell py-2">{{ owner }}</td>
    </tr>
</template>

<script setup>
import {defineProps} from "vue";
import {addTime, dateTime} from "@/composables/moment.js";
import {getStatusText} from "@/composables/status";

defineProps({
  id: Intl,
  owner: String,
  eventname: String,
  description: String,
  duration: Intl,
  startTime: String,
  status: Intl,
});
</script>
