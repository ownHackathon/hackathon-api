<template>
    <FailureView/>
    <form @submit.prevent="login">
        <div class="flex justify-center w-full pt-6">
            <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
                <div class="div-table-header">
                    Anmelden
                </div>
                <div class="div-table-content">
                    <div class="mb-6">
                        <label class="label" for="username">Benutzername</label>
                        <input id="username" v-model="payload.username" class="input" name="username" placeholder="Dein Benutzername" required type="text"/>
                    </div>

                    <div class="mb-6">
                        <label class="label" for="password">Passwort</label>
                        <input id="password" v-model="payload.password" class="input" name="password" required type="password"/>
                    </div>

                    <div class="flex">
                        <!-- @TODO Remember me function -->
                        <input id="remember" aria-describedby="remember" class="checkbox" disabled type="checkbox"/>
                        <label class="label" for="remember">Angemeldet bleiben</label>
                    </div>

                    <div class="flex justify-center pb-6">
                        <RouterLink :to="{name: 'user_password_forgotten'}">Passwort vergessen?</RouterLink>
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
import {onMounted, reactive} from "vue";
import {useRouter} from 'vue-router';
import useUserService from "@/composables/UserService";
import FailureView from "@/views/user/compontents/FailureView";
import {useToast} from "vue-toastification";

const userService = useUserService();
const router = useRouter();

const toast = useToast();
const payload = reactive({
    username: '',
    password: '',
});

onMounted(() => {
    if (userService.isAuthenticated()) {
        router.push('/');
    }
});

async function login() {
    await axios
        .post("/api/login", payload,)
        .then((response) => {
            if (response && response.status === 200) {
                localStorage.setItem('token', response.data.token);
                userService.loadUser();
                router.back();
                toast.success('Anmeldung erfolgreich');
            } else {
                document.getElementById('error-message').innerHTML = 'Benutzer/Passwort Kombination fehlerhaft';
                document.getElementById('error-container').classList.remove('hidden');
                toast.error('Anmeldevorgang nicht erfolgreich');
            }
        })
        .catch(() => {
            document.getElementById('error-message').innerHTML = 'Unbekannter Fehler, später noch einmal versuchen';
            document.getElementById('error-container').classList.remove('hidden');
            toast.error('Anmeldevorgang nicht erfolgreich');
        });
}
</script>
