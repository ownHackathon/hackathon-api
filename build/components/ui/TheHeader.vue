<template>
  <header class="bg-gray-800 border-b border-gray-600 w-full">
    <nav class="nav-container">

        <div class="flex md:hidden justify-start" @click="showMenu = !showMenu">
          <button class="menu-button">
            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <title>Menu</title>
              <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
            </svg>
          </button>
        </div>
        <router-link class="text-white no-underline hover:text-white hover:no-underline" :to="{name: 'home'}">
          <span class="text-xl pl-2"><i class="em em-grinning"></i>(Black) Hackathon</span>
        </router-link>

        <div :class="showMenu ? 'flex' : 'hidden'" class="menu-elements">
          <router-link @click="showMenu = !showMenu" class="nav-entry" :to="{name: 'event_about'}">Info</router-link>
          <router-link @click="showMenu = !showMenu" class="nav-entry" :to="{name: 'event_list'}">Events</router-link>
          <router-link @click="showMenu = !showMenu" class="nav-entry" :to="{name: 'home'}">Blog</router-link>
          <router-link @click="showMenu = !showMenu" class="nav-entry" :to="{name: 'home'}">Über</router-link>
        </div>

        <div class="flex flex-auto justify-end">
          <button id="nav-user-toggle" class="flex items-center px-3 py-2 text-gray-200 hover:text-white hover:border-white">
            <font-awesome-icon icon="user-lock" />
          </button>
        </div>

    </nav>
  </header>
</template>

<script>
export default {
  name: "TheHeader",
  data() {
    return {
      showMenu: false,
    };
  },
}
</script>

<style lang="scss">
nav span {
  color: #e43c5c;
}
nav a {
  color: #e5e7eb;
}

.nav-container {
  @apply flex mx-auto md:justify-between md:items-center;
}

.nav-entry {
  @apply  no-underline py-2 px-4;
}

.menu-button {
  @apply items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-white hover:border-white;
}

.menu-elements {
 @apply flex-col absolute top-9 bg-gray-600
        md:flex md:relative md:top-0 md:bg-gray-800 md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0
}

</style>
