<script setup>

import { ref } from 'vue';

import ProfileImage from '@/Components/Images/ProfileImage.vue';
import Header from '@/Components/Templates/Header.vue';

import { useModalStore } from '@/Pinia/ModalStore';
import { useUserStore } from '@/Pinia/UserStore';

const userStore = useUserStore();
const modalStore = useModalStore();

await userStore.set_user();

const url = ref(null);
if (userStore.user.user_profile_image && userStore.user.user_profile_image.path) {
    url.value = 'storage/user_profile_images/' + userStore.user.user_profile_image.path;
}

</script>

<template>
    <section class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg tw-py-14 tw-px-10 sm:tw-px-20 tw-mt-10">
        <Header h="2" value="Moje dane osobowe" :center="true"></Header>

        <hr class="tw-mt-8 tw-mb-12">
        <div class="tw-flex tw-flex-col tw-text-center tw-mb-16">
            <ProfileImage :profile_image="userStore.user.user_profile_image"></ProfileImage>

            <div class="username-wrapper tw-mt-8 tw-text-3xl sm:tw-text-4xl tw-font-bold tw-mb-10">
                {{ `${userStore.user.user_profiles.first_name}` }} <span class="tw-text-blue-700">{{
                    `${userStore.user.user_profiles.last_name}`
                }}</span>
            </div>
            <div class="phone-wrapper tw-mb-3 tw-text-lg sm:tw-text-2xl">
                <i class="fa-sharp fa-solid fa-phone tw-text-green-700 tw-mr-2"></i> {{
                    userStore.user.user_profiles.phone_number
                }}
            </div>
            <div class="email-wrapper tw-text-lg sm:tw-text-2xl">
                <i class="fa-solid fa-envelope tw-text-sky-700 tw-mr-2"></i> {{ userStore.user.user_profiles.email }}
            </div>
        </div>
        <div class="tw-flex tw-flex-row tw-justify-center sm:tw-justify-end">
            <p class="tw-text-blue-500 hover:tw-text-blue-800 hover:tw-cursor-pointer hover:tw-underline tw-w-fit"
                @click="modalStore.visibility.profile_image = true">Zmień zdjęcie</p>
        </div>
    </section>
</template>
