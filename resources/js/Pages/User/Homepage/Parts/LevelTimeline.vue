<script setup>

import LevelInfo from '@/Components/Templates/LevelInfo.vue';
import Header from '@/Components/Templates/Header.vue';

import { useUserStore } from '@/Pinia/UserStore';

const userStore = useUserStore();
await userStore.set_user();

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
})

</script>

<template>
    <v-card class="!tw-mt-10 !tw-bg-gray-900 !tw-shadow-xl">
        <template v-slot:title>
            <div class="tw-text-center tw-text-4xl tw-text-gray-100">Sprawdź swoje postępy!</div>
        </template>
        <v-card-text>
            <div class="tw-grid tw-gap-4 tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-mt-8">
                <LevelInfo :levels="levels" :user="userStore.user" :index="0"></LevelInfo>
                <LevelInfo :levels="levels" :user="userStore.user" :index="1"></LevelInfo>
                <LevelInfo :levels="levels" :user="userStore.user" :index="2"></LevelInfo>
                <LevelInfo :levels="levels" :user="userStore.user" :index="3"></LevelInfo>
            </div>
        </v-card-text>
    </v-card>
</template>
