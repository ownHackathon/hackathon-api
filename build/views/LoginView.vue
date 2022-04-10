<template>
  <div id="error-container" class="hidden flex justify-center w-full pt-6">
    <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
      <div class="div-table-header">
        Fehler bei der Anmeldung
      </div>
      <div class="div-table-content content-center">
        <span id="error-message" class="text-red-500 "></span>
      </div>
    </div>
  </div>
  <form @submit.prevent="login">
  <div class="flex justify-center w-full pt-6">
    <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
      <div class="div-table-header">
        Anmelden
      </div>
      <div class="div-table-content">
        <div class="mb-6">
          <label class="label" for="username">Benutzername</label>
          <input class="input" type="text" v-model="payload.username" id="username" name="username" placeholder="Dein Benutzername" required />
        </div>

        <div class="mb-6">
          <label class="label" for="password">Passwort</label>
          <input class="input" type="password" v-model="payload.password" name="password" id="password" required />
        </div>

        <div class="flex">
          <input class="checkbox" type="checkbox" id="remember" aria-describedby="remember" disabled/>
          <label class="label" for="remember">Angemeldet bleiben</label>
        </div>

        <div class="flex justify-center pb-6">
          <RouterLink to="/">Passwort vergessen?</RouterLink>
        </div>
        <div class="flex justify-center">
          <button class="button" type="submit">Anmelden</button>
        </div>
      </div>
    </div>
  </div>
  </form>
</template>

<script setup>
import axios from "axios";
import {onBeforeMount, reactive} from "vue";
import {useRouter} from 'vue-router';
import {useUserStore} from "@/store/user";

const userStore = useUserStore();
const router = useRouter();
const payload = reactive({
  username: '',
  password: '',
});

onBeforeMount(() => {
  if (userStore.isAuthenticated) {
    router.back();
  }
})

async function login() {
  const response = await axios
      .post("/login", payload, )
      .catch((err) => {
        document.getElementById('error-message').innerHTML = 'Unbekannter Fehler, später noch einmal versuchen';
        document.getElementById('error-container').classList.remove('hidden');
        console.error(err);
      })

  if (response && response.status === 200) {
    localStorage.setItem('token', response.data.token);
    userStore.isAuthenticated = true;
    await router.back();
  } else {
    document.getElementById('error-message').innerHTML = 'Benutzer/Passwort Kombination fehlerhaft';
    document.getElementById('error-container').classList.remove('hidden');
  }
}
</script>
