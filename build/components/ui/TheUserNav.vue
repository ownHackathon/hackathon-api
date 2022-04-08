<template>
    <div class="flex flex-auto justify-end">
      <button id="nav-user-toggle" @click="isMenuOpen = !isMenuOpen" class="flex items-center px-3 py-2 text-gray-200 hover:text-white hover:border-white">
        <font-awesome-icon icon="user-lock" />
      </button>

      <div :class="isMenuOpen ? 'flex' : 'hidden'" class="flex-col absolute top-14 border border-gray-600 bg-gray-800 z-10">
        <span class="text-center font-bold">Willkommen</span>
        <router-link @click="isMenuOpen = false" class="nav-entry" :to="{name: 'login'}">Anmelden</router-link>
        <router-link @click="isMenuOpen = false" class="nav-entry" :to="{name: 'home'}">Registrieren</router-link>
      </div>
    </div>
</template>

<script setup>
  import { ref } from 'vue';

  const isMenuOpen = ref(false);
</script>

<style lang="scss" scoped>
  .nav-entry {
    @apply  no-underline py-2 px-4 text-gray-300 hover:text-white;
  }
</style>
