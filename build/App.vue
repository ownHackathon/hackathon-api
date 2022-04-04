<template>
  <div>
    <RouterView />
  </div>
</template>

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

a:hover  {
  color: #ea6981;
  text-decoration: none;
}

a[target="_blank"]:after {
  content: url("/build/assets/box-arrow-up-right.svg");
  padding: 0px 3px 0px 3px;
  bottom: 5px;
  position: relative;
}
</style>
