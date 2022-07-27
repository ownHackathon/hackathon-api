<template>
  <header>
    <TheTopNavBar/>
  </header>
</template>

<script setup>
import TheTopNavBar from "@/components/ui/TheTopNavBar";</script>

<style lang="scss">
header {
  @apply bg-gray-800;
  @apply border-b;
  @apply border-gray-600;
  @apply w-full;
}
</style>
