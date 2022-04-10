<template>
  <nav class="flex py-3 mx-auto md:justify-between md:items-center">
    <div class="flex md:hidden justify-start" @click="isMenuOpen = !isMenuOpen">
      <button class="items-center px-3 py-2 rounded text-gray-500  hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <title>Menu</title>
          <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
        </svg>
      </button>
    </div>
    <router-link :to="{name: 'home'}" class="text-white no-underline hover:text-white hover:no-underline">
      <span class="text-xl pl-2"><i class="em em-grinning"></i>(Black) Hackathon</span>
    </router-link>

    <div :class="isMenuOpen ? 'flex' : 'hidden'" class="flex-col absolute top-14 border border-gray-600 bg-gray-800 z-10
      md:border-0 md:flex md:relative md:top-0 md:bg-gray-800 md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0">
      <router-link :to="{name: 'event_general_information'}" class="nav-entry" @click="isMenuOpen = false">Info</router-link>
      <router-link :to="{name: 'event_list'}" class="nav-entry" @click="isMenuOpen = false">Events</router-link>
      <router-link :to="{name: 'home'}" class="nav-entry" @click="isMenuOpen = false">Blog</router-link>
      <router-link :to="{name: 'about'}" class="nav-entry" @click="isMenuOpen = false">Über</router-link>
    </div>

    <TheUserNav/>
  </nav>
</template>

<script setup>
import {ref} from 'vue';
import TheUserNav from "@/components/ui/TheUserNav";

const isMenuOpen = ref(false);
</script>

<style lang="scss" scoped>
nav span {
  color: #e43c5c;
}

.nav-entry {
  @apply no-underline py-2 px-4 text-gray-300 hover:text-white;
}
</style>
