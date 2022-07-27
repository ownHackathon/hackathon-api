<template>
  <footer>
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
  @apply flex;
  @apply items-center;
  @apply justify-between;
  @apply flex-wrap;
  @apply min-h-full;
  @apply p-2;
  @apply mx-1;
  @apply bg-gray-900;
  @apply text-zinc-400;
  @apply text-xs;
  @apply rounded;
}
</style>
