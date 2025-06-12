<script setup>

import { computed, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits([
    'toggle-user',
    // 'update-user-profile-image'
]);

const disabled = ref(false);

const departure_date_display = computed(() => {
    if (props.user.ready_to_departure_dates && props.user.ready_to_departure_dates.departure_date) {
        if (props.user.ready_to_departure_dates.departure_date != '' && props.user.ready_to_departure_dates.departure_date != null) {
            return props.user.ready_to_departure_dates.departure_date;
        } else {
            return '-';
        }
    } else {
        return '-';
    }
})

const accept_user_image = async () => {
    disabled.value = true;
    let response = await axios.patch(route('accept.user.profile.img'), {
        id: props.user.id,
        status: 3
    });

    emit('update-user-profile-image', response.data);
    disabled.value = false;
}

</script>

<template>
    <v-card class="tw-shadow-lg tw-rounded tw-p-10">
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-justify-between tw-items-center">
                <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
                    <i class="fa-solid fa-circle-user text-main"></i>
                    <div>Dane użytkownika</div>
                </div>
                <p class="tw-text-blue-600 tw-font-bold tw-underline hover:tw-text-blue-900 hover:tw-cursor-pointer tw-text-sm"
                    @click="$emit('toggle-user')">Edytuj dane</p>
            </div>
        </template>
        <v-card-text>
            <div class="tw-flex tw-flex-row tw-mt-6">
                <div class="tw-grow">PESEL</div>
                <div class="tw-grow tw-text-right">{{ user.pesel }}</div>
            </div>
            <div class="tw-flex tw-flex-row">
                <div class="tw-grow">Imię i nazwisko</div>
                <div class="tw-grow tw-text-right">{{ user.user_profiles.first_name }} {{
                    user.user_profiles.last_name }}</div>
            </div>
            <div class="tw-flex tw-flex-row tw-mt-1">
                <div class="tw-grow">E-mail</div>
                <div class="tw-grow tw-text-right">{{ user.user_profiles.email ?? '-' }}</div>
            </div>
            <div class="tw-flex tw-flex-row tw-mt-1">
                <div class="tw-grow">Numer telefonu</div>
                <div class="tw-grow tw-text-right">{{ user.user_profiles.phone_number ?? '-' }}</div>
            </div>
            <div class="tw-flex tw-flex-row tw-mt-6">
                <div class="tw-grow">Przejdź do profilu w CRM</div>
                <div class="tw-grow tw-text-right">
                    <a :href="`https://local.grupa-veritas.pl/#/opiekunki/${user.user_profiles.crt_id_caretaker}`">
                        <i class="fa-solid fa-globe tw-text-2xl tw-text-indigo-600 hover:tw-text-indigo-800"></i>
                    </a>
                </div>
            </div>

            <h2 class="tw-font-semibold tw-text-xl tw-leading-tight tw-mt-14"><i
                    class="fa-sharp fa-solid fa-calendar-circle-user"></i>
                Dodatkowe informacje</h2>
            <div class="tw-flex tw-flex-row tw-mt-6">
                <div class="tw-grow">Data gotowości do wyjazdu</div>
                <div class="tw-grow tw-text-right">{{ departure_date_display }}</div>
            </div>
        </v-card-text>

    </v-card>
</template>
