<script setup>

import { ref } from 'vue';

import ProfileImage from '@/Components/Images/ProfileImage.vue';
import Header from '@/Components/Templates/Header.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

defineEmits([
    'open'
])

const url = ref(null);
if (props.user.user_profile_image && props.user.user_profile_image.path) { 
    url.value = 'storage/user_profile_images/' + props.user.user_profile_image.path;
}

</script>

<template>
    <section class="bg-gray-100 overflow-hidden shadow-xl rounded-lg py-14 px-10 sm:px-20 mt-10">
        <Header
            h="2"
            value="Moje dane osobowe"
            :center="true"
        ></Header>

        <hr class="mt-8 mb-12">
        <div class="flex flex-col text-center mb-16">
            <ProfileImage :profile_image="user.user_profile_image"></ProfileImage>

            <div class="username-wrapper mt-8 text-3xl sm:text-4xl font-bold mb-10">
                {{ `${user.user_profiles.first_name}` }} <span class="text-blue-700">{{ `${user.user_profiles.last_name}` }}</span>
            </div>
            <div class="phone-wrapper mb-3 text-lg sm:text-2xl">
                <i class="fa-sharp fa-solid fa-phone text-green-700 mr-2"></i> {{ user.user_profiles.phone_number }}
            </div>
            <div class="email-wrapper text-lg sm:text-2xl">
                <i class="fa-solid fa-envelope text-sky-700 mr-2"></i> {{ user.user_profiles.email }}
            </div>
        </div>
        <div class="flex flex-row justify-center sm:justify-end">
            <p class="text-blue-500 hover:text-blue-800 hover:cursor-pointer hover:underline w-fit" @click="$emit('open', 'profile_image')">Zmień zdjęcie</p>
        </div>
    </section>
</template>