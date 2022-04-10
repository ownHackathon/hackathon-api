<template>
  <footer class="flex items-center justify-between flex-wrap min-h-10 p-2 mx-1 bg-gray-900 text-zinc-400 text-xs rounded">
    <div>
      Lizenz:
      <a href="https://github.com/BibaltiK/Hackathon/blob/master/LICENSE" target="_blank">
        BSD 3-Clause
      </a>
    </div>
    <div>
      Community Hackathon auf dem
      <router-link :to="{name: 'discord'}" target="_blank">Discord Server</router-link>
      von <a href="https://www.youtube.com/VitalijMik" target="_blank">BlackScorp</a>
    </div>
    <div>
      Hackathon @ <a href="https://github.com/BibaltiK/Hackathon" target="_blank">GitHub</a>
    </div>

  </footer>
</template>

<style lang="scss">
footer {
  margin-top: 50px;
}
</style>
