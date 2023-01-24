<template>
  <nav class="nav-top">
    <div class="nav-top-main-description" @click="isMenuOpen = !isMenuOpen">
      <button class="nav-top-main-description-button">
        <svg class="nav-top-main-description-button-svg" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <title>Menu</title>
          <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
        </svg>
      </button>
    </div>
    <router-link :to="{name: 'home'}" class="text-white no-underline hover:text-white hover:no-underline">
      <span class="text-xl pl-2"><i class="em em-grinning"></i>(Black) Hackathon</span>
    </router-link>

    <div :class="isMenuOpen ? 'flex' : 'hidden'" class="nav-top-list">
      <router-link :to="{name: 'event_general_information'}" class="nav-list-entry" @click="isMenuOpen = false">Info</router-link>
      <router-link :to="{name: 'event_list'}" class="nav-list-entry" @click="isMenuOpen = false">Events</router-link>
      <router-link :to="{name: 'home'}" class="nav-list-entry" @click="isMenuOpen = false">Blog</router-link>
      <router-link :to="{name: 'about'}" class="nav-list-entry" @click="isMenuOpen = false">Über</router-link>
    </div>

    <TheUserNav/>
  </nav>
</template>

<script setup>
import {ref} from 'vue';
import TheUserNav from "@/components/ui/TheUserNav";

const isMenuOpen = ref(false);
</script>

<style lang="scss">
nav span {
  color: #e43c5c;
}

.nav-top {
  @apply flex;
  @apply py-3;
  @apply mx-auto;
  @apply md:justify-between;
  @apply md:items-center;
}

.nav-top-main-description {
  @apply flex;
  @apply md:hidden;
  @apply justify-start;
}

.nav-top-main-description-button {
  @apply items-center;
  @apply px-3;
  @apply py-2;
  @apply rounded;
  @apply text-gray-500;
  @apply hover:text-white;
  @apply hover:border-white;
}

.nav-top-main-description-button-svg {
  @apply fill-current;
  @apply h-3;
  @apply w-3;
}

.nav-top-list {
  @apply flex-col;
  @apply absolute;
  @apply top-14;
  @apply border;
  @apply border-gray-600;
  @apply bg-gray-800;
  @apply z-10;
  @apply md:border-0;
  @apply md:flex;
  @apply md:relative;
  @apply md:top-0;
  @apply md:bg-gray-800;
  @apply md:space-y-0;
  @apply md:flex-row;
  @apply md:items-center;
  @apply md:space-x-10;
  @apply md:mt-0;
}

.nav-list-entry {
  @apply no-underline;
  @apply py-2;
  @apply px-4;
  @apply text-gray-300;
  @apply hover:text-white;
}
</style>
