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
    <div class="bg-gray-100 shadow-xl rounded p-10">
        <div class="flex sm:flex-row flex-col justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                <i class="fa-solid fa-circle-user text-main"></i> 
                Dane użytkownika
            </h2>
            <p class="text-blue-600 font-bold underline hover:text-blue-900 hover:cursor-pointer" @click="$emit('toggle-user')">Edytuj dane</p>
        </div>
        <!-- <div class="flex flex-row mt-6">
            <div class="grow text-center relative">
                <div 
                    v-if="user.user_profile_image && user.user_profile_image.path" 
                    class="profile-img profile-img-md border-2 border-gray-800 shadow-xl relative" 
                    :style="`background-image: url(/storage/user_profile_images/${user.user_profile_image.path});`">
                    <i v-if="user.user_profile_image && user.user_profile_image.status == 3" class="fa-solid fa-circle-check text-green-600 text-3xl image-icon"></i>
                    <i v-else class="fa-sharp fa-solid fa-circle-xmark text-3xl image-icon"></i>
                </div>
                <i v-else class="fa-solid fa-circle-user img-default"></i>
            </div>
        </div>
        <div class="flex flex-row mt-6" v-if="user.user_profile_image && user.user_profile_image.status == 1">
            <div class="grow text-center relative">
                <button class="bg-green-800 text-gray-100 px-6 py-2 rounded hover:bg-green-600 disabled:bg-green-600  disabled:text-gray-100 hover:cursor-pointer" @click="accept_user_image" :disabled="disabled">
                    <i class="fa-sharp fa-solid fa-thumbs-up mr-2"></i>
                    Akceptuj zdjęcie
                </button>
            </div>
        </div> -->
        <div class="flex flex-row mt-6">
            <div class="grow">PESEL</div>
            <div class="grow text-right">{{ user.pesel }}</div>
        </div>
        <div class="flex flex-row">
            <div class="grow">Imię i nazwisko</div>
            <div class="grow text-right">{{ user.user_profiles.first_name }} {{
                user.user_profiles.last_name }}</div>
        </div>
        <div class="flex flex-row mt-1">
            <div class="grow">E-mail</div>
            <div class="grow text-right">{{ user.user_profiles.email ?? '-' }}</div>
        </div>
        <div class="flex flex-row mt-1">
            <div class="grow">Numer telefonu</div>
            <div class="grow text-right">{{ user.user_profiles.phone_number ?? '-' }}</div>
        </div>
        <div class="flex flex-row mt-6">
            <div class="grow">Przejdź do profilu w CRM</div>
            <div class="grow text-right">
                <a :href="`https://local.grupa-veritas.pl/#/opiekunki/${user.user_profiles.crt_id_caretaker}`">
                    <i class="fa-solid fa-globe text-2xl text-indigo-600 hover:text-indigo-800"></i>
                </a>
            </div>
        </div>

        <h2 class="font-semibold text-xl leading-tight mt-14"><i class="fa-sharp fa-solid fa-calendar-circle-user"></i>
            Dodatkowe informacje</h2>
        <div class="flex flex-row mt-6">
            <div class="grow">Data gotowości do wyjazdu</div>
            <div class="grow text-right">{{ departure_date_display }}</div>
        </div>

    </div>
</template>