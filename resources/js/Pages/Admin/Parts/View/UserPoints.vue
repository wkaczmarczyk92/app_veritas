<script setup>

import { level, levelColor } from '@/Components/Functions/Level.vue';

defineProps({
    user: {
        type: Object,
        required: true
    },
    levels: {
        type: Object,
        required: true
    }
})

</script>

<template>
    <v-card class="tw-shadow-lg tw-rounded tw-p-10">
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-justify-between">
                <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
                    <i class="fa-solid fa-chart-user tw-text-green"></i>
                    <div>Zebrane punkty</div>
                </div>
                <p class="tw-font-bold  hover:tw-text-blue-900 hover:tw-cursor-pointer tw-text-sm">
                    Poziom - <span :class="levelColor(user.user_profiles.level)"><b>{{
                        level(levels,
                            user.user_profiles.level).toUpperCase() }}</b></span></p>
            </div>
        </template>
        <v-card-text>
            <div class="tw-flex tw-flex-row tw-mt-6">
                <div class="tw-grow tw-border-b tw-border-dotted tw-border-gray-500">Aktualna ilość punktów</div>
                <div class="tw-grow tw-text-right tw-border-b tw-border-dotted tw-border-gray-500">{{
                    user.user_profiles.current_points ?? '-' }}</div>
            </div>
            <div class="tw-flex tw-flex-row tw-mt-3">
                <div class="tw-grow tw-border-b tw-border-dotted tw-border-gray-500">Łączna ilość punktów</div>
                <div class="tw-grow tw-text-right tw-border-b tw-border-dotted tw-border-gray-500">{{
                    user.user_profiles.total_points ?? '-' }}</div>
            </div>
            <div class="tw-flex tw-flex-row tw-mt-3">
                <div class="tw-grow tw-border-b tw-border-dotted tw-border-gray-500">Łączna ilość przepracowanych dni
                </div>
                <div class="tw-grow tw-text-right tw-border-b tw-border-dotted tw-border-gray-500">{{
                    user.user_profiles.total_days ?? '-' }}</div>
            </div>

            <h2 class="tw-font-semibold tw-text-xl tw-leading-tight tw-mt-14"><i class="fa-solid fa-user-headset"></i>
                Dane rekrutera</h2>
            <div class="tw-flex tw-flex-row tw-mt-6">
                <div class="tw-grow">Imię</div>
                <div class="tw-grow tw-text-right">{{ user.user_profiles.recruiter_first_name ?? '-' }}</div>
            </div>
            <div class="tw-flex tw-flex-row tw-mt-1">
                <div class="tw-grow">Nazwisko</div>
                <div class="tw-grow tw-text-right">{{ user.user_profiles.recruiter_last_name ?? '-' }}</div>
            </div>
        </v-card-text>
    </v-card>
</template>
