<template>
  <TheHeader />
  <main class="container-fluid content">
    <RouterView />
  </main>
  <TheFooter />
</template>

<script>
import TheHeader from "@/components/ui/TheHeader";
import TheFooter from "@/components/ui/TheFooter";
export default {
  name: "MainLayout",
  components: {
    TheFooter,
    TheHeader,
  }
}
</script>

<style lang="scss">

</style>
