<template>
  <div class="user-nav">
    <button class="user-nav-button" @click="isMenuOpen = !isMenuOpen">
      <font-awesome-icon v-if="user.isAuthenticated()" icon="user"/>
      <font-awesome-icon v-else icon="user-lock"/>
    </button>

    <div v-if="user.isAuthenticated()" :class="isMenuOpen ? 'flex' : 'hidden'"
         class="user-nav-entry">
      <router-link :to="{name: 'logout'}" class="nav-entry" @click="isMenuOpen = false">Abmelden</router-link>
    </div>
    <div v-else :class="isMenuOpen ? 'flex' : 'hidden'" class="user-nav-entry">
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

<style lang="scss">
.user-nav {
  @apply flex;
  @apply flex-auto;
  @apply justify-end;
}

.user-nav-button {
  @apply flex;
  @apply items-center;
  @apply px-3;
  @apply py-2;
  @apply text-gray-200;
  @apply hover:text-white;
  @apply hover:border-white;
}

.user-nav-entry {
  @apply flex-col;
  @apply absolute;
  @apply top-14;
  @apply md:top-16;
  @apply border;
  @apply border-gray-600;
  @apply bg-gray-800;
  @apply z-10;
}

.nav-entry {
  @apply no-underline;
  @apply py-2;
  @apply px-4;
  @apply text-gray-300;
  @apply hover:text-white;
}
</style>
