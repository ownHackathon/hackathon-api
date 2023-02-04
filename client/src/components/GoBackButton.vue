<template>
  <div class="mt-2 mb-2">
    <button class="button" @click="goBack">
      zurück
    </button>
  </div>
</template>

<script setup>
import {useRouter} from "vue-router";

const router = useRouter();

function goBack() {
  return router.go(-1);
}
</script>

<style scoped>

</style>
