<template>
    <FailureView/>
    <form @submit.prevent="submitPassword">
        <div class="flex justify-center w-full pt-6">
            <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
                <div class="div-table-header">
                    Neues Password erstellen
                </div>
                <div class="div-table-content">
                    <div class="mb-6">
                        <label class="label" for="password">neues Password</label>
                        <input id="password" v-model="payload.password" class="input" name="password" required type="password"/>
                    </div>

                    <div class="flex justify-center">
                        <button class="button" type="submit">Passwort ändern</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import {onMounted, reactive} from "vue";
import axios from "axios";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import FailureView from "@/views/user/compontents/FailureView";

const route = useRoute();
const router = useRouter();
const toast = useToast();
const payload = reactive({
    password: '',
})

onMounted(async () => {
    await axios
        .get(`/api/user/password/${route.params.token}`)
        .then((response) => {
            if (response.status !== 200) {
                router.push({name: 'invalid_token'});
            }
        })
        .catch((error) => {
            console.log(error)
            router.push({name: 'invalid_token'});
        })
});

async function submitPassword() {
    await axios
        .post(`/api/user/password/${route.params.token}`, payload)
        .then((response) => {
            if (response.status === 200) {
                toast.success('Passwort wurde erfolgreich geändert.');
                router.push({name: 'login'});
            } else {
                router.push({name: 'error'});
            }
        })
        .catch((error) => {
            console.log(error)
            router.push({name: 'error'});
        })
}

</script>

<style lang="scss">

</style>
