<template>
  <div class="div-table">
    <div class="div-table-header">
      Daten
    </div>
    <div class="div-table-content-row">
      <div class="grow">Start:</div>
      <div class="flex-initial">{{ dateTime(startTime) }} Uhr</div>
    </div>
    <div class="div-table-content-row">
      <div class="grow">Laufzeit:</div>
      <div class="flex-initial">{{ duration }} Tage</div>
    </div>
    <div class="div-table-content-row">
      <div class="grow">Ende:</div>
      <div class="flex-initial">{{ dateTime(addTime(startTime, duration)) }} Uhr</div>
    </div>
    <div class="div-table-content-row">
      <div class="grow">Status:</div>
      <div class="flex-initial">{{ getStatusText(status) }}</div>
    </div>
  </div>
</template>

<script setup>
import {defineProps} from "vue";
import {addTime, dateTime} from '@/composables/moment.js';
import {getStatusText} from "@/composables/status";

defineProps({
  startTime: Date,
  duration: Intl,
  status: Intl,
});
</script>

<style lang="scss">

</style>
