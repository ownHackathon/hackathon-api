<template>
    <FailureView
        :header="formData.failureView.header"
        :content="formData.failureView.content"
    />
    <form @submit.prevent="checkForm" novalidate>
        <div class="div-table pt-6">
            <div class="div-table-header">
                Event erstellen
            </div>
            <div class="div-table-content">
                <div class="mb-6">
                    <label class="label" for="eventTitle">
                        Titel
                        <span v-show="formData.eventTitle.labelPlaceholderText" id="eventTitleLabelPlaceholder" class="text-red-900 text-xs">
              {{ formData.eventTitle.labelPlaceholderText }}
            </span>
                    </label>
                    <input id="eventTitle"
                           @input="toggleEventTitlePlaceholder($event.target.value)"
                           v-model="payload.title"
                           :placeholder="formData.eventTitle.placeholder"
                           :required="formData.eventTitle.required"
                           class="input"
                           name="evenTitle"
                           type="text"
                    />
                </div>
            </div>
            <div class="div-table-content">
                <div class="mb-6">
                    <label class="label" for="eventDescription">
                        Kurzbeschreibung
                        <span v-show="formData.eventDescription.labelPlaceholderText" id="eventDescriptionLabelPlaceholder" class="text-red-900 text-xs">
              {{ formData.eventDescription.labelPlaceholderText }}
            </span>
                    </label>
                    <input id="eventDescription"
                           @input="toggleEventDescriptionPlaceholder($event.target.value)"
                           v-model="payload.description"
                           :placeholder="formData.eventDescription.placeholder"
                           :required="formData.eventDescription.required"
                           class="input"
                           name="eventDescription"
                           type="text"
                    />
                </div>
            </div>
            <div class="div-table-content">
                <div class="mb-6">
                    <label for="eventText" class="label">
                        Beschreibung
                        <span v-show="formData.eventText.labelPlaceholderText" id="eventTextLabelPlaceholder" class="text-red-900 text-xs">
              {{ formData.eventText.labelPlaceholderText }}
            </span>
                    </label>
                    <textarea id="eventText"
                              @input="toggleEventTextPlaceholder($event.target.value)"
                              v-model="payload.eventText"
                              :placeholder="formData.eventText.placeholder"
                              :required="formData.eventText.required"
                              rows="10"
                              class="textarea"
                    >
          </textarea>
                </div>
            </div>
            <div class="div-table-content">
                <div class="mb-6">
                    <label for="eventStartTime" class="label">
                        Start-Zeit
                        <span v-show="formData.eventStartTime.labelPlaceholderText" id="eventTextLabelPlaceholder" class="text-red-900 text-xs">
              {{ formData.eventStartTime.labelPlaceholderText }}
            </span>
                    </label>
                    <input id="eventStartTime"
                           v-model="payload.startTime"
                           @input="toggleEventStartTimePlaceholder($event.target.value)"
                           :min="formData.eventStartTime.time.min"
                           :max="formData.eventStartTime.time.max"
                           class="input"
                           name="eventStartTime"
                           required
                           type="datetime-local"/>
                </div>
            </div>
            <div class="div-table-content">
                <div class="mb-6">
                    <label for="eventDuration" class="label">
                        Laufzeit in Tagen
                        <span v-show="formData.eventDuration.labelPlaceholderText" id="eventDurationLabelPlaceholder" class="text-red-900 text-xs">
              {{ formData.eventDuration.labelPlaceholderText }}
            </span>
                    </label>
                    <input id="eventDuration"
                           @input="toggleEventDurationPlaceholder($event.target.value)"
                           v-model="payload.duration"
                           class="input"
                           name="eventDuration"
                           required
                           type="number"
                    />
                </div>
            </div>
            <input id="csrfToken" name="csrfToken" v-model="payload.csrfToken" type="hidden" />
            <div class="div-table-content">
                <div class="mb-6">
                    <div class="flex justify-center">
                        <button class="button" type="submit">Event erstellen</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import FailureView from "@/views/user/components/FailureView";
import {useToast} from "vue-toastification";
import {addTime, databaseDateTime} from "@/composables/moment";
import axios from 'axios';
import router from "@/router";

const toast = useToast();
const nowDate = new Date();

const payload = reactive({
    title: '',
    description: '',
    eventText: '',
    startTime: '',
    duration: 14,
    csrfToken: '',
});

const formData = ref({
    failureView: {
        header: "Fehler",
        content: "Bitte alle Felder korrekt ausfüllen",
    },
    eventTitle: {
        labelPlaceholderText: '',
        placeholder: "mindestens 3 Zeichen und maximal 50 Zeichen",
        required: true,
    },
    eventDescription: {
        labelPlaceholderText: '',
        placeholder: "mindestens 10 Zeichen und maximal 255 Zeichen",
        required: true,
    },
    eventText: {
        labelPlaceholderText: '',
        placeholder: "mindestens 50 Zeichen und maximal 8192 Zeichen",
        required: true,
    },
    eventStartTime: {
        labelPlaceholderText: '',
        time: {
            min: '',
            max: '',
        },
        required: true,
    },
    eventDuration: {
        labelPlaceholderText: '',
        required: true,
    },
});

onMounted(() => {
    payload.startTime = databaseDateTime(addTime(nowDate, 30));
    formData.value.eventStartTime.time.min = databaseDateTime(addTime(nowDate, 1));
    formData.value.eventStartTime.time.max = databaseDateTime(addTime(nowDate, 356));
});

function checkResponseError(data) {
    document.getElementById('error-container').classList.remove('hidden');
    formData.value.failureView.content = data.message;
    if (data.data) {
        formData.value.failureView.content += " with Code " + JSON.stringify(data.data);
    }
}

async function createEvent() {
    await axios
        .post("/api/event", payload)
        .then((response) => {
            if (response.status === 201) {
                console.log(response);
                toast.success("Event erfolgreich erstellt");
                router.push({name: "event_list"});
            } else {
                formData.value.failureView.content = "Unbekannter Fehler";
                document.getElementById('error-container').classList.remove('hidden');
                toast.error("Event wurde nicht erstellt");
            }
        })
        .catch((error) => {
            toast.error("Event wurde nicht erstellt");
            if (error.response.status === 404) {
                checkResponseError(error.response.data);
            } else {
                router.push({name: "error"});
            }
        });
}

function toggleEventTitlePlaceholder(value) {
    if ((value.length > 0 && value.length < 3)) {
        formData.value.eventTitle.labelPlaceholderText = '(noch mindestens ' + (3 - value.length) + ' Zeichen)';
    } else if (value.length > 50) {
        formData.value.eventTitle.labelPlaceholderText = '( ' + (value.length - 50) + ' Zeichen zuviel)';
    } else {
        formData.value.eventTitle.labelPlaceholderText = '';
    }
}

function toggleEventDescriptionPlaceholder(value) {
    if ((value.length > 0 && value.length < 10)) {
        formData.value.eventDescription.labelPlaceholderText = '(noch mindestens ' + (10 - value.length) + ' Zeichen)';
    } else if (value.length > 255) {
        formData.value.eventDescription.labelPlaceholderText = '( ' + (value.length - 255) + ' Zeichen zuviel)';
    } else {
        formData.value.eventDescription.labelPlaceholderText = '';
    }
}

function toggleEventTextPlaceholder(value) {
    if ((value.length > 0 && value.length < 50)) {
        formData.value.eventText.labelPlaceholderText = '(noch mindestens ' + (50 - value.length) + ' Zeichen)';
    } else if (value.length > 255) {
        formData.value.eventText.labelPlaceholderText = '( ' + (value.length - 8192) + ' Zeichen zuviel)';
    } else {
        formData.value.eventText.labelPlaceholderText = '';
    }
}

function toggleEventStartTimePlaceholder(value) {
    const startTimeDiff = Math.floor((new Date(value).getTime() - nowDate.getTime()) / 1000 / 60 / 60 / 24);
    if ((startTimeDiff < 1 || startTimeDiff > 356)) {
        formData.value.eventStartTime.labelPlaceholderText = 'Die Startzeit sollte zwischen 1 und 365 Tagen betragen';
    } else {
        formData.value.eventStartTime.labelPlaceholderText = '';
    }
}

function toggleEventDurationPlaceholder(value) {
    if ((value < 1 || value > 356)) {
        formData.value.eventDuration.labelPlaceholderText = 'Die Laufzeit sollte zwischen 1 und 365 Tagen dauern';
    } else {
        formData.value.eventDuration.labelPlaceholderText = '';
    }
}

function checkForm() {
    let formFilled = true;
    document.getElementById('error-container').classList.add('hidden');
    document.getElementById('eventTitle').classList.add('input');
    document.getElementById('eventTitle').classList.remove('input_failure');
    document.getElementById('eventDescription').classList.add('input');
    document.getElementById('eventDescription').classList.remove('input_failure');
    document.getElementById('eventText').classList.add('textarea');
    document.getElementById('eventText').classList.remove('textarea_failure');
    document.getElementById('eventDuration').classList.add('input');
    document.getElementById('eventDuration').classList.remove('input_failure');

    if (payload.title.length < 3 || payload.title.length > 50) {
        document.getElementById('eventTitle').classList.remove('input');
        document.getElementById('eventTitle').classList.add('input_failure');
        formFilled = false;
    }
    if (payload.description.length < 10 || payload.description.length > 255) {
        document.getElementById('eventDescription').classList.remove('input');
        document.getElementById('eventDescription').classList.add('input_failure');
        formFilled = false;
    }
    if (payload.eventText.length < 50 || payload.eventText.length > 8192) {
        document.getElementById('eventText').classList.remove('textarea');
        document.getElementById('eventText').classList.add('textarea_failure');
        formFilled = false;
    }
    if (payload.duration < 1 || payload.duration > 356) {
        document.getElementById('eventDuration').classList.remove('input');
        document.getElementById('eventDuration').classList.add('input_failure');
        formFilled = false;
    }

    if (payload.title.length === 0 ||
        payload.description.length === 0 ||
        payload.eventText.length === 0 ||
        payload.startTime.length === 0 ||
        (payload.duration < 1 || payload.duration > 356)
    ) {
        formFilled = false;
    }

    if (formFilled) {
        payload.startTime = databaseDateTime(payload.startTime);
        createEvent();
    } else {
        document.getElementById('error-container').classList.remove('hidden');
        toast.error('Fehler in der Eingabe');
    }
}

</script>
