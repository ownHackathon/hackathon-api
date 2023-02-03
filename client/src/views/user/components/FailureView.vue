<template>
  <div id="error-container" class="hidden flex justify-center w-full pt-6">
    <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
      <div class="div-table-header">
        {{ header }}
      </div>
      <div class="div-table-content content-center">
        <span id="error-message" class="text-red-500 ">{{content}}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import {defineProps} from "vue";

defineProps({
  header: String,
  content: String,
})
</script>
