<template>
    <div v-if="isShowTopic">
        <div class="div-table">
            <div class="div-table-header">
                <div>Thema: <span class="font-bold">{{ props.topic.title }}</span></div>
            </div>
            <div class="div-table-content">
                <div class="prose max-w-max text-gray-400">
                    <Markdown :source="props.topic.description"/>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="div-table">
            <div class="div-table-header">
                <div>Thema:</div>
            </div>
            <div v-if="isUserInParticipantList">
                <div class="div-table-content">
                    <span class="flex justify-center pb-6">Noch kein Thema? Na dann mal fix zur...</span>
                    <div class="flex justify-center">
                        <button class="button">Themenauswahl</button>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="div-table-content">
                    <span class="flex justify-center pb-6">Themenauswahl hat noch nicht statt gefunden.</span>
                    <div class="div-table-content">
                        Für das Themenvoting sind nur
                        <RouterLink :to="{name: 'register'}">registrierte</RouterLink>
                        ,
                        <RouterLink :to="{name: 'login'}">angemeldete</RouterLink>
                        sowie teilnehmende Benutzer zugelassen.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, defineProps} from "vue";
import Markdown from "vue3-markdown-it";

const props = defineProps({
    topic: Object,
    eventSubscribeStatus: Intl,
});

const isShowTopic = computed(() => {
    return props.topic !== undefined;
});

const isUserInParticipantList = computed( () => {
    return props.eventSubscribeStatus;
});

</script>

<style lang="scss">

</style>
