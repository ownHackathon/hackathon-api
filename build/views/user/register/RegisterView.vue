<template>
  <FailureView/>

  <form @submit.prevent="register" action="/register">
    <div class="flex justify-center w-full pt-6">
      <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
        <div class="div-table-header">
          Registrierung
        </div>
        <div class="div-table-content">
          <div class="mb-6">
            <label class="label" for="username">Benutzername</label>
            <input id="username" v-model="payload.username" class="input" name="username" required type="text"/>
          </div>
          <div class="mb-6">
            <label class="label" for="password">Passwort *mindestens 6 Zeichen</label>
            <input id="password" v-model="payload.password" class="input" name="password" required type="password"/>
          </div>

          <div class="mb-6">
            <label class="label" for="email">E-Mail</label>
            <input id="email" v-model="payload.email" class="input" name="email" required type="email"/>
          </div>

          <div class="flex justify-center">
            <button class="button" type="submit">Registrieren</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import FailureView from '@/views/user/compontents/FailureView'
import {reactive} from "vue";
import {useRouter} from 'vue-router';
import axios from "axios";
import {useToast} from "vue-toastification";

const toast = useToast();
const router = useRouter();
const payload = reactive({
  username: '',
  password: '',
  email: '',
});

async function register() {
  const response = await axios
      .post("/api/user/register", payload,)
      .catch(() => {
        document.getElementById('error-message').innerHTML = 'Unbekannter Fehler, später noch einmal versuchen';
        document.getElementById('error-container').classList.remove('hidden');
      });

  if (response && response.status === 200) {
    toast.success('Registrierung erfolgreich');
    router.back();
  } else {
    document.getElementById('error-message').innerHTML = 'Kombination gegebener Daten fehlerhaft';
    document.getElementById('error-container').classList.remove('hidden');
    toast.error('Fehler bei der Registrierung');
  }
}
</script>
