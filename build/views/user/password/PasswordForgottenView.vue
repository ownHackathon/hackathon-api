<template>
    <FailureView/>
    <form v-show="!passwordRequestSubmited" @submit.prevent="submitEmail">
        <div class="flex justify-center w-full pt-6">
            <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
                <div class="div-table-header">
                    Passwort vergessen
                </div>
                <div class="div-table-content">
                    <div class="mb-6">
                        <label class="label" for="email">E-Mail</label>
                        <input id="email" v-model="payload.email" class="input" name="email" required type="email"/>
                    </div>

                    <div class="flex justify-center">
                        <button class="button" type="submit">Neues Passwort anfordern</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div v-show="passwordRequestSubmited">
        <div class="flex justify-center w-full pt-6">
            <p>E-Mail mit der Anforderung zum Ändern des Passwortes versendet.</p>
        </div>
    </div>
</template>

<script setup>
import FailureView from "@/views/user/compontents/FailureView";
import {reactive, ref} from "vue";
import axios from "axios";

const passwordRequestSubmited = ref(false);
const payload = reactive({
    email: '',
});

async function submitEmail() {
    await axios
        .post('/user/password/forgotten', payload)
        .then((response) => {
            if (response.status === 200) {
                console.log(response.data);
                passwordRequestSubmited.value = true;
            } else {
                console.log(response.data);
            }
        })
        .catch((error) => {
            console.log(error);
        });
}
</script>

<style lang="scss">

</style>
