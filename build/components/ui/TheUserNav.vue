<template>
  <div class="flex flex-auto justify-end">
    <button class="flex items-center px-3 py-2 text-gray-200 hover:text-white hover:border-white" @click="isMenuOpen = !isMenuOpen">
      <font-awesome-icon v-if="user.isAuthenticated()" icon="user"/>
      <font-awesome-icon v-else icon="user-lock"/>
    </button>

    <div v-if="user.isAuthenticated()" :class="isMenuOpen ? 'flex' : 'hidden'"
         class="flex-col absolute top-14 md:top-16 border border-gray-600 bg-gray-800 z-10">
      <router-link :to="{name: 'logout'}" class="nav-entry" @click="isMenuOpen = false">Abmelden</router-link>
    </div>
    <div v-else :class="isMenuOpen ? 'flex' : 'hidden'" class="flex-col absolute top-14 md:top-16 border border-gray-600 bg-gray-800 z-10">
      <router-link :to="{name: 'login'}" class="nav-entry" @click="isMenuOpen = false">Anmelden</router-link>
      <router-link :to="{name: 'home'}" class="nav-entry" @click="isMenuOpen = false">Registrieren</router-link>
    </div>
  </div>
</template>

<script setup>
import {ref} from 'vue';
import useUser from "@/composables/user";

const user = useUser();
const isMenuOpen = ref(false);
</script>

<style lang="scss" scoped>
.nav-entry {
  @apply no-underline py-2 px-4 text-gray-300 hover:text-white;
}
</style>
