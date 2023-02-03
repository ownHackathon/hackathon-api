<template>
  <tr class="border-b bg-gray-800 border-gray-700">
    <td class="xl:table-cell py-2 px-2 font-bold">{{props.topic}}</td>
    <td class="xl:table-cell py-2 px-2">{{props.description}}</td>
  </tr>
</template>

<script setup>

// eslint-disable-next-line no-undef
const props = defineProps({
      uuid: String,
      topic: String,
      description: String,
    }
);
</script>

<style scoped>
</style>
