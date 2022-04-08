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
</script>
