<template>
  <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <th scope="row" class="py-2 px-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
      <RouterLink :to="{name: 'home'}"> {{ eventname }}</RouterLink>
    </th>
    <td class="hidden md:flex py-2">{{ description }}</td>
    <td class="py-2" >
     {{ dateTime(startTime) }}
      </td>
    <td class="hidden lg:flex py-2">{{ duration }}</td>
    <td class="py-2">{{ status }}</td>
    <td class="hidden sm:flex py-2">{{ owner }}</td>
  </tr>
</template>

<script setup>
import { defineProps } from "vue";
import { dateTime } from "@/composables/moment.js";

defineProps({
  eventname: String,
  description: String,
  startTime: Date,
  duration: Int32Array,
  status: Boolean,
  owner: Int32Array
})



</script>
