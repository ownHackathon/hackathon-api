<template>
  <footer class="footer row">
    <div class="col-md-4 text-start small">
      Lizenz:
      <a href="https://github.com/BibaltiK/Hackathon/blob/master/LICENSE" target="_blank">
        BSD 3-Clause
      </a>
    </div>
    <div class="col-md-4 text-center small">
      Community Hackathon auf dem <router-link to="/discord" target="_blank">Discord Server</router-link>
      von <a href="https://www.youtube.com/VitalijMik" target="_blank">BlackScorp</a>
    </div>
    <div class="col-md-4 text-end small">
      Hackathon @ <a href="https://github.com/BibaltiK/Hackathon" target="_blank">GitHub</a>
    </div>
  </footer>
</template>

<script>
export default {
  name: "TheFooter"
}
</script>

<style lang="scss">
.footer {
  margin-top: 50px;
  background-color: #222222;
  width: 99%;
}
</style>
