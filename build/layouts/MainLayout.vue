<template>
  <TheHeader />
  <main class="px-4 max-w-8xl">
    <RouterView class="prose max-w-max flex-auto " />
  </main>
  <TheFooter />
</template>

<script>

import TheFooter from "@/components/ui/TheFooter";
import TheHeader from "@/components/ui/TheHeader";

export default {
  name: "MainLayout",
  components: {
    TheHeader,
    TheFooter,
  }
}
</script>

<style lang="scss">

</style>
