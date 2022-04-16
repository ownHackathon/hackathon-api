<template>
  <TheHeader />
  <main class="px-4 top-16 max-w-8xl">
    <RouterView />
  </main>
  <TheFooter />
</template>

<script setup>
import TheFooter from "@/components/ui/TheFooter";
import TheHeader from "@/components/ui/TheHeader";
import {onBeforeMount} from "vue";
import useUser from "@/composables/user";

const {loadUser} = useUser();

  onBeforeMount(() => {
    loadUser();
  })
</script>

<style lang="scss">
html,
body {
  @apply bg-gray-900 #{!important};
  @apply text-gray-200;
  @apply h-full;
}

a {
  color: #e43c5c;
  text-decoration: none;
}

a:hover {
  color: #ea6981;
  text-decoration: none;
}

a[target="_blank"]:after {
  content: url("/build/assets/box-arrow-up-right.svg");
  padding: 0px 3px 0px 3px;
  bottom: 5px;
  position: relative;
}

.div-table {
  @apply relative overflow-x-auto shadow rounded;
}

.div-table-header {
  @apply flex py-1 px-2 text-xs uppercase bg-gray-700 text-gray-400;
}

.div-table-content {
  @apply py-1 px-2 text-gray-400 bg-gray-800;
}

.div-table-content-row {
  @apply flex border-t border-gray-700 py-1 px-2 text-gray-400 bg-gray-800;
}

.label {
  @apply block mb-2 text-sm font-medium text-gray-300;
}

.input {
  @apply p-2.5 border text-sm rounded-lg block w-full border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 bg-gray-600;
}

.checkbox {
  @apply w-4 h-4 mx-2 bg-gray-800 border-gray-600 rounded border focus:ring-blue-600 ring-offset-gray-800;
}

.button {
  @apply pb-1.5 pt-0.5 px-3 mr-2 mb-2 text-sm font-medium focus:outline-none focus:z-10 focus:ring-gray-700 rounded border bg-gray-700 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-600;
}
</style>
