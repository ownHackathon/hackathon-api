<template>
  <div class="top-6 flex w-full justify-center items-center">
    <h1 class="font-bold">{{ title }}</h1>
  </div>

  <div class="p-3 flex w-full justify-center items-center">
    <p class="text-xs text-gray-400 ">
      {{ description }}
    </p>
  </div>

  <div class="div-table py-6">
    <div class="div-table-header">
      <div class="grow">Beschreibung</div>
    </div>
    <div class="div-table-content">
      <div class="prose max-w-max text-gray-400">
        <Markdown :source="fulltext"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import {defineProps} from "vue";
import Markdown from "vue3-markdown-it";

defineProps({
  title: String,
  description: String,
  fulltext: String,
});
</script>

<style lang="scss">
h1 {
  font-size: 2rem;
  color: #e43c5c;
}
</style>
