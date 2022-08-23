<template>
    <FailureView/>
    <form @submit.prevent="submitEmail">
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
</template>

<script setup>
import FailureView from "@/views/user/compontents/FailureView";
import {reactive} from "vue";

const payload = reactive({
    email: '',
});

async function submitEmail() {

}
</script>

<style lang="scss">

</style>
