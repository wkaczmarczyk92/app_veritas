<script setup>

import { ref } from 'vue';

import ProfileImage from '@/Components/Images/ProfileImage.vue';
import Header from '@/Components/Templates/Header.vue';

import { useModalStore } from '@/Pinia/ModalStore';
import { useUserStore } from '@/Pinia/UserStore';
import DisplayRow3 from '@/Components/HTML/DisplayRow3.vue';
import { format } from '@/Components/Functions/DateFormat.vue';

const user_store = useUserStore();
const modalStore = useModalStore();

await user_store.set_user();

const url = ref(null);
if (user_store.user.user_profile_image && user_store.user.user_profile_image.path) {
    url.value = 'storage/user_profile_images/' + user_store.user.user_profile_image.path;
}

</script>

<template>
    <v-card class="!tw-border-t !tw-border-[#fc9003] !tw-border-t-8">
        <v-card-text>
            <div class="tw-mt-4 tw-flex tw-flex-row tw-justify-center tw-relative tw-group">
                <ProfileImage :profile_image="user_store.user.user_profile_image" class="">
                </ProfileImage>
                <div
                    class="tw-absolute tw-bottom-6 tw-left-[50%] -tw-translate-x-[50%]
           tw-bg-[#1a1a1a] tw-text-white tw-flex tw-flex-row tw-gap-2 tw-text-base
           tw-items-center tw-px-4 tw-py-1 tw-rounded-2xl

           tw-opacity-0 tw-translate-y-2 tw-pointer-events-none
           tw-transition-all tw-duration-300 tw-ease-in-out tw-delay-150
           group-hover:tw-opacity-100 group-hover:tw-translate-y-0 group-hover:tw-pointer-events-auto hover:tw-cursor-pointer">
                    <i class="fas fa-pen"></i>
                    <div @click="modalStore.visibility.profile_image = true">Zmień zdjęcie</div>
                </div>
            </div>

            <div class="tw-bg-[#f8f9fa] tw-mt-10 tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                <div class="">Imię i nazwisko</div>
                <div class="tw-font-bold tw-text-lg">{{ user_store.user.user_profiles.first_name }} {{
                    user_store.user.user_profiles.last_name }}</div>
            </div>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4 tw-mt-4">
                <div class="tw-bg-[#f8f9fa] tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                    <div class="">PESEL</div>
                    <div class="tw-font-bold tw-text-lg">{{ user_store.user.pesel }}</div>
                </div>
                <div class="tw-bg-[#f8f9fa] tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                    <div class="">Numer telefonu</div>
                    <div class="tw-font-bold tw-text-lg">{{ user_store.user.user_profiles.phone_number }}</div>
                </div>
            </div>

            <div class="tw-bg-[#f8f9fa] tw-mt-4 tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                <div class="">Email</div>
                <div class="tw-font-bold tw-text-lg">{{ user_store.user.user_profiles.email ?? 'brak' }}</div>
            </div>

            <div class="tw-bg-[#f8f9fa] tw-mt-4 tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                <div class="">Dyspozycyjność</div>
                <div class="tw-font-bold tw-text-lg">{{ user_store.user?.ready_to_departure_dates?.departure_date ??
                    'brak' }}</div>
            </div>

            <div class="tw-bg-[#f8f9fa] tw-mt-4 tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                    <i class="far fa-clock"></i>
                    Konto aktywne od: {{ user_store.user.activated_at ? format(user_store.user.activated_at) : 'brak' }}
                </div>
                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                    <i class="far fa-calendar"></i>
                    Konto utworzone: {{ format(user_store.user.created_at) }}
                </div>
            </div>
        </v-card-text>
    </v-card>
    <!-- <v-card title="Moje dane osobowe">
        <v-card-text>
            <div class="tw-flex tw-flex-col tw-gap-2">
                <DisplayRow3 name="Imię" :value="user_store.user.user_profiles.first_name" />
                <DisplayRow3 name="Nazwisko" :value="user_store.user.user_profiles.last_name" />
                <DisplayRow3 name="PESEL" :value="user_store.user.pesel" />
                <DisplayRow3 name="Numer telefonu" :value="user_store.user.user_profiles.phone_number" />
                <DisplayRow3 name="Adres e-mail" :value="user_store.user.user_profiles.email ?? 'brak'" />
                <div class="tw-mt-8">
                    <ProfileImage :profile_image="user_store.user.user_profile_image"></ProfileImage>
                </div>
            </div>
        </v-card-text>
        <v-card-actions>
            <div class="tw-flex tw-flex-row tw-w-full">
                <v-btn @click="modalStore.visibility.profile_image = true" variant="tonal" color="#2563eb">Zmień
                    zdjęcie</v-btn>
            </div>
        </v-card-actions>
    </v-card> -->
</template>
