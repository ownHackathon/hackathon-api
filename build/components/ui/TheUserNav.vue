<template>
    <div class="flex flex-auto justify-end">
      <button @click="isMenuOpen = !isMenuOpen" class="flex items-center px-3 py-2 text-gray-200 hover:text-white hover:border-white">
        <font-awesome-icon v-if="userStore.isAuthenticated" icon="user" />
        <font-awesome-icon v-else icon="user-lock" />
      </button>

      <div v-if="userStore.isAuthenticated" :class="isMenuOpen ? 'flex' : 'hidden'" class="flex-col absolute top-14 md:top-16 border border-gray-600 bg-gray-800 z-10">
        <router-link @click="isMenuOpen = false" class="nav-entry" :to="{name: 'logout'}">Abmelden</router-link>
      </div>
      <div v-else :class="isMenuOpen ? 'flex' : 'hidden'" class="flex-col absolute top-14 md:top-16 border border-gray-600 bg-gray-800 z-10">
        <router-link @click="isMenuOpen = false" class="nav-entry" :to="{name: 'login'}">Anmelden</router-link>
        <router-link @click="isMenuOpen = false" class="nav-entry" :to="{name: 'home'}">Registrieren</router-link>
      </div>
    </div>
</template>

<script setup>
  import { ref } from 'vue';
  import {useUserStore} from "@/store/user";

  const isMenuOpen = ref(false);
  const userStore = useUserStore();
</script>

<style lang="scss" scoped>
  .nav-entry {
    @apply  no-underline py-2 px-4 text-gray-300 hover:text-white;
  }
</style>
