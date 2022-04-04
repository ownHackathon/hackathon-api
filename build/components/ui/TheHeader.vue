<template>
  <header >
    <nav class="nav-container">
      <div class="flex items-center flex-shrink-0 text-white mr-6">
        <router-link class="text-white no-underline hover:text-white hover:no-underline" :to="{name: 'home'}">
          <span class="text-xl pl-2"><i class="em em-grinning"></i>(Black) Hackathon</span>
        </router-link>
      </div>

      <div class="flex justify-start lg:hidden">
        <button id="nav-toggle" @click="toggleMenu" class="menu-button">
          <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
          </svg>
        </button>
      </div>

      <div :class="showMenu">
        <div class="list-reset lg:flex justify-start flex-1 items-center">
            <router-link @click="toggleMenu" class="nav-entry" :to="{name: 'event_about'}">Info</router-link>
            <router-link @click="toggleMenu" class="nav-entry" :to="{name: 'event_list'}">Events</router-link>
            <router-link @click="toggleMenu" class="nav-entry" :to="{name: 'home'}">Blog</router-link>
            <router-link @click="toggleMenu" class="nav-entry" :to="{name: 'test_view'}">Über</router-link>
        </div>
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
      isOpenMenu: false,
      menuHidden: 'menu-elements-hidden',
    };
  },

  methods: {
    toggleMenu () {
      if (this.isOpenMenu) {
        this.menuHidden = 'menu-elements-hidden';
        this.isOpenMenu = false;
      } else {
        this.menuHidden = 'menu-elements';
        this.isOpenMenu = true;
      }
    }
  },
  computed: {
    showMenu() {
      return this.menuHidden;
    }
  }

}
</script>

<style lang="scss">
nav span {
  color: #e43c5c;
}
nav a {
  color: #e5e7eb;
}

nav ol,
ul,
menu {
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-container {
  @apply flex bg-gray-800 border-b border-gray-600 w-full top-0 z-10;
}

.nav-entry {
  @apply  no-underline py-2 px-4;
}

.menu-button {
  @apply flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-white hover:border-white;
}

.menu-elements {
  @apply flex ;
}

.menu-elements-hidden {
  @apply hidden flex-initial lg:flex lg:items-center lg:w-auto lg:block pt-6 lg:pt-0;
}

</style>
