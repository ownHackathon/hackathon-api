<template>
  <TheHeader/>
  <main class="main">
    <RouterView/>
  </main>
  <TheFooter/>
</template>

<script setup>
import TheFooter from "@/components/ui/TheFooter";
import TheHeader from "@/components/ui/TheHeader";
import {onBeforeMount} from "vue";
import useUserService from "@/composables/UserService";

const {loadUser} = useUserService();

onBeforeMount(() => {
  loadUser();
});
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
  padding: 0 3px;
  bottom: 5px;
  position: relative;
}

.main {
  @apply px-4;
  @apply top-16;
  @apply max-w-full;
}

.div-table {
  @apply relative;
  @apply overflow-x-auto;
  @apply shadow;
  @apply rounded;
}

.div-table-header {
  @apply flex;
  @apply py-1;
  @apply px-2;
  @apply text-xs;
  @apply uppercase;
  @apply bg-gray-700;
  @apply text-gray-400;
}

.div-table-content {
  @apply py-1;
  @apply px-2;
  @apply text-gray-400;
  @apply bg-gray-800;
}

.div-table-content-row {
  @apply flex;
  @apply border-t;
  @apply border-gray-700;
  @apply py-1;
  @apply px-2;
  @apply text-gray-400;
  @apply bg-gray-800;
}

.label {
  @apply block;
  @apply mb-2;
  @apply text-sm;
  @apply font-medium;
  @apply text-gray-300;
}

.input {
  @apply p-2.5;
  @apply border;
  @apply text-sm;
  @apply rounded-lg;
  @apply block;
  @apply w-full;
  @apply border-gray-600;
  @apply placeholder-gray-400;
  @apply text-white;
  @apply focus:ring-blue-500;
  @apply focus:border-blue-500;
  @apply bg-gray-600;
}

.input_failure {
  @apply p-2.5;
  @apply border;
  @apply text-sm;
  @apply rounded-lg;
  @apply block;
  @apply w-full;
  @apply border-red-600;
  @apply placeholder-gray-400;
  @apply text-white;
  @apply focus:ring-blue-500;
  @apply focus:border-blue-500;
  @apply bg-gray-600;
}

.textarea {
  @apply block;
  @apply p-2.5;
  @apply w-full;
  @apply text-sm;
  @apply bg-gray-600;
  @apply rounded-lg;
  @apply border;
  @apply focus:ring-blue-500;
  @apply focus:border-blue-500;
  @apply border-gray-600;
  @apply placeholder-gray-400;
  @apply text-white;
}

.textarea_failure {
  @apply block;
  @apply p-2.5;
  @apply w-full;
  @apply text-sm;
  @apply bg-gray-600;
  @apply rounded-lg;
  @apply border;
  @apply focus:ring-blue-500;
  @apply focus:border-blue-500;
  @apply border-red-600;
  @apply placeholder-gray-400;
  @apply text-white;
}

.checkbox {
  @apply w-4;
  @apply h-4;
  @apply mx-2;
  @apply bg-gray-800;
  @apply border-gray-600;
  @apply rounded;
  @apply border;
  @apply focus:ring-blue-600;
  @apply ring-offset-gray-800;
}

.button {
  @apply pb-1.5;
  @apply pt-0.5;
  @apply px-3;
  @apply mr-2;
  @apply mb-2;
  @apply text-sm;
  @apply font-medium;
  @apply focus:outline-none;
  @apply focus:z-10;
  @apply focus:ring-gray-700;
  @apply rounded;
  @apply border;
  @apply bg-gray-700;
  @apply text-gray-400;
  @apply border-gray-600;
  @apply hover:text-white;
  @apply hover:bg-gray-600;
}
</style>
