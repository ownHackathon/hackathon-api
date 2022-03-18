<template>
  <TheHeader/>
  <main class="container-fluid content">
    <RouterView/>
  </main>
  <TheFooter/>
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
.content {
  width: 100%;
  background-color: #2c3034;
  color: #f0f0f0;
  padding: 10px;
}
</style>
