<template>
    <div class="flex justify-center w-full pt-6">
        <div class="div-table w-5/6 md:w-3/6 2xl:w-2/6">
            <div class="div-table-header">
                Fehlerhaftes Token
            </div>
            <div class="div-table-content">
                <p>
                    Der Token für den Besuch der gewünschten Seite ist Fehlerhaft.<br>
                    Bitte prüfen oder zurückgehen zu
                    <router-link :to="{name: 'home'}">Hauptseite</router-link>
                </p>
            </div>
        </div>
    </div>
</template>
