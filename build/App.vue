<template>
  <div>
    <RouterView/>
  </div>
</template>

<script>
</script>

<style lang="scss">
@import '~bootstrap/scss/bootstrap.scss';
html,
body {
  font-family: "Open Sans", sans-serif;
  color: #f0f0f0;
  background-color: #1a1e21;
  height: 100%;
  padding: 0;
  margin: 0;
}

a {
  color: #e43c5c;
  text-decoration: none;
}

a:hover {
  color: #ea6981;
  text-decoration: none;
}

h1, h2, h3, h4, h5, h6 {
  font-family: "Nunito", sans-serif;
}

.text-justify {
  hyphens: auto;
  text-align: justify;
}

.text-small {
  font-size: small;
}

.text-smaller {
  font-size: smaller;
}

.text-xx-small {
  font-size: xx-small;
}

.content {
  width: 100%;
  background-color: #2c3034;
  color: #f0f0f0;
  padding: 10px;
}

.external {
  font-size: xx-small;
  font-variant-position: super;
  color: lightgray;
}
</style>
