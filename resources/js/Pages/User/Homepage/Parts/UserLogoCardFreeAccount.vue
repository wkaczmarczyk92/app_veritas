<script setup lang="ts">

import { ref, onBeforeMount } from 'vue';
import { router } from '@inertiajs/vue3';

import CountUp from 'vue-countup-v3';
import type { ICountUp, CountUpOptions } from 'vue-countup-v3'

import Header from '@/Components/Templates/Header.vue';
import { level, levelColor, neonColor } from '@/Components/Functions/Level.vue';
import { date_of_last_update, date_of_next_update } from '@/Components/Functions/DateFormat.vue';
import MButton from '@/Components/Buttons/MButton.vue';

import { useUserStore } from '@/Pinia/UserStore';
import { useModalStore } from '@/Pinia/ModalStore';


const modalStore = useModalStore();



const last_points_insert_date = ref('??')

const userStore = useUserStore();
await userStore.set_user();

// onBeforeMount(async () => {
//     console.log('tutaj')
// })

</script>

<template>
    <div :style="`--height: ${height}`"></div>
    <v-card :title="`Witaj ${userStore.username()}`" class="!tw-shadow-2xl !tw-bg-gray-100">
        <template v-slot:title>
            <div class="!tw-text-3xl tw-text-center tw-mt-10">
                Witaj, <span class="tw-text-blue-700">{{ userStore.username() }}!</span>
            </div>
        </template>
        <v-card-text>
            <div class="tw-grid tw-grid-cols-7 tw-mt-6 tw-mb-10">
                <v-divider :thickness="4" class="border-opacity-75 tw-col-start-4 tw-border-[#1a6db9]"
                    color="red"></v-divider>
            </div>
            <div class="tw-flex tw-justify-center tw-mt-12">
                <div class="tw-w-full sm:tw-w-4/5 md:tw-w-4/6 lg:tw-w-1/2 svg-container-free svg-container">
                    <img src="/images/svg/VER_group3.svg">
                </div>
            </div>
        </v-card-text>
        <v-card-actions class="tw-mb-10 tw-mt-20">
            <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-center tw-items-center tw-w-full tw-gap-6">
                <v-btn size="x-large" color="#ea580c" variant="tonal" :href="route('offer.index_free_account')">
                    Zobacz oferty
                </v-btn>
                <v-btn :href="route('premium.index')" size="x-large" color="#16a34a" variant="tonal">
                    Konto premium
                </v-btn>
            </div>
        </v-card-actions>
    </v-card>
</template>
<style>
.svg-container-free::before {
    height: calc(100% - 1px);
    background-color: #1a6db9;
}

@keyframes backgroundFill {
    from {
        height: 0;
    }

    to {
        height: calc(100% - 1px);
    }
}
</style>