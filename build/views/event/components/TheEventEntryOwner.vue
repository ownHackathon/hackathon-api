<template>
  <div class="flex p-3 text-gray-800">
    <div class="grow">Event erstellt am: {{ date(createTime) }}</div>
    <div class="flex-initial pl-2">von:
      <RouterLink to="/user/">{{ owner }}</RouterLink>
    </div>
  </div>
</template>

<script setup>
import {defineProps} from "vue";
import {date} from '@/composables/moment.js';
defineProps({
  createTime: Date,
  owner: String,
})
</script>

<style lang="scss">

</style>
