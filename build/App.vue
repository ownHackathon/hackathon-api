<template>
  <div>
    <RouterView />
  </div>
</template>

<style lang="scss">
a[target="_blank"]:after {
  content: url("/build/assets/box-arrow-up-right.svg");
  padding: 0px 3px 0px 3px;
  bottom: 5px;
  position: relative;
}
</style>
