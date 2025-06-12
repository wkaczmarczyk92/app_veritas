<script setup>
import { format } from '@/Components/Functions/DateFormat.vue'
import DisplayRow3 from '@/Components/HTML/DisplayRow3.vue';
import ProfileImage from '@/Components/Images/ProfileImage.vue';

const props = defineProps({
    user: {
        type: Object,
        reuqired: true
    }
})

console.log('profil uzytkownika', props.user)

</script>

<template>
    <v-card class="!tw-border-t !tw-border-[#fc9003] !tw-border-t-8">
        <v-card-text>
            <div class="tw-mt-4 tw-flex tw-flex-row tw-justify-center tw-relative tw-group">
                <ProfileImage :profile_image="null" class="">
                </ProfileImage>
            </div>
            <div class="tw-bg-[#f8f9fa] tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base tw-mt-10">
                <div class="">Imię i nazwisko</div>
                <div class="tw-font-bold tw-text-lg">{{ user.user_profiles.first_name }} {{
                    user.user_profiles.last_name }}</div>
            </div>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4 tw-mt-4">
                <div class="tw-bg-[#f8f9fa] tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                    <div class="">PESEL</div>
                    <div class="tw-font-bold tw-text-lg">{{ user.pesel }}</div>
                </div>
                <div class="tw-bg-[#f8f9fa] tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                    <div class="">Numer telefonu</div>
                    <div class="tw-font-bold tw-text-lg">{{ user.user_profiles.phone_number }}</div>
                </div>
            </div>

            <div class="tw-bg-[#f8f9fa] tw-mt-4 tw-flex tw-flex-col tw-p-4 tw-rounded-2xl tw-text-base">
                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                    <i class="far fa-clock"></i>
                    Konto aktywne od: {{ user.activated_at ? format(user.activated_at) : 'brak' }}
                </div>
                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                    <i class="far fa-calendar"></i>
                    Konto utworzone: {{ format(user.created_at) }}
                </div>
            </div>
        </v-card-text>
    </v-card>
    <!-- <v-card :title="user.user_profiles.first_name + ' ' + user.user_profiles.last_name" class="!tw-shadow-xl">
        <v-card-text>
            <div class="tw-flex tw-flex-col tw-gap-2">
                <DisplayRow3 name="PESEL" :value="user.pesel" />
                <DisplayRow3 name="Numer telefonu" :value="user.user_profiles.phone_number" />
            </div>
        </v-card-text>
        <v-card-actions>
            <div class="tw-flex tw-flex-row tw-justify-between tw-w-full font-italic tw-text-sm">
                <div>
                    Konto aktywne od: {{ format(user.activated_at) }}
                </div>
                <div>
                    Konto utworzone: {{ format(user.created_at) }}
                </div>
            </div>
        </v-card-actions>
    </v-card> -->
</template>