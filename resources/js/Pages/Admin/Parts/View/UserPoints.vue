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
    <div class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-p-10">
        <div class="tw-flex tw-flex-row">
            <h2 class="tw-font-semibold tw-text-xl tw-leading-tight tw-grow"><i
                    class="fa-solid fa-chart-user tw-text-green"></i> Zebrane punkty</h2>
            <div class="tw-grow tw-text-right">Poziom - <span :class="levelColor(user.user_profiles.level)"><b>{{
                level(levels,
                    user.user_profiles.level).toUpperCase() }}</b></span></div>
        </div>
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
    </div>
</template>
