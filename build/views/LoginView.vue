<template>
  <form @submit.prevent="login">
  <div class="flex justify-center w-full pt-6">
    <DivTable class="w-5/6 md:w-3/6 2xl:w-2/6">
      <DivTableHeader>
        Anmelden
      </DivTableHeader>
      <DivTableContent>
        <div class="mb-6">
          <TLabel for="username">Benutzername</TLabel>
          <TInput type="text" :value="payload.username" id="username" name="username" minlength="3" placeholder="Dein Benutzername" required />
        </div>

        <div class="mb-6">
          <TLabel for="password">Passwort</TLabel>
          <TInput type="password" :value="payload.password" name="password" minlength="6" id="password" required />
        </div>

        <div class="flex">
          <TInputCheckbox id="remember" aria-describedby="remember" disabled />
          <TLabel for="remember">Angemeldet bleiben</TLabel>
        </div>

        <div class="flex justify-center pb-6">
          <RouterLink to="/">Passwort vergessen?</RouterLink>
        </div>
        <div class="flex justify-center">
          <TButton type="submit">Anmelden</TButton>
        </div>
      </DivTableContent>
    </DivTable>
  </div>
  </form>
</template>

<script setup>
import DivTable from "@/components/form/DivTable";
import DivTableHeader from "@/components/form/DivTableHeader";
import DivTableContent from "@/components/form/DivTableContent";
import TButton from "@/components/form/TButton";
import TInput from "@/components/form/TInput";
import TLabel from "@/components/form/TLabel";
import TInputCheckbox from "@/components/form/TInputCheckbox";
import axios from "axios";
import {reactive} from "vue";


const payload = reactive({
  username: '',
  password: '',
});

async function login() {
  await axios
      .post("/login/", payload)
}
</script>
