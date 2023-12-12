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
    <div class="bg-gray-100 shadow-xl rounded p-10">
        <div class="flex flex-row">
            <h2 class="font-semibold text-xl leading-tight grow"><i
                    class="fa-solid fa-chart-user text-green"></i> Zebrane punkty</h2>
            <div class="grow text-right">Poziom - <span
                    :class="levelColor(user.user_profiles.level)"><b>{{ level(levels,
                        user.user_profiles.level).toUpperCase() }}</b></span></div>
        </div>
        <div class="flex flex-row mt-6">
            <div class="grow border-b border-dotted border-gray-500">Aktualna ilość punktów</div>
            <div class="grow text-right border-b border-dotted border-gray-500">{{
                user.user_profiles.current_points ?? '-' }}</div>
        </div>
        <div class="flex flex-row mt-3">
            <div class="grow border-b border-dotted border-gray-500">Łączna ilość punktów</div>
            <div class="grow text-right border-b border-dotted border-gray-500">{{
                user.user_profiles.total_points ?? '-' }}</div>
        </div>
        <div class="flex flex-row mt-3">
            <div class="grow border-b border-dotted border-gray-500">Łączna ilość przepracowanych dni
            </div>
            <div class="grow text-right border-b border-dotted border-gray-500">{{
                user.user_profiles.total_days ?? '-' }}</div>
        </div>

        <h2 class="font-semibold text-xl leading-tight mt-14"><i class="fa-solid fa-user-headset"></i>
            Dane rekrutera</h2>
        <div class="flex flex-row mt-6">
            <div class="grow">Imię</div>
            <div class="grow text-right">{{ user.user_profiles.recruiter_first_name ?? '-' }}</div>
        </div>
        <div class="flex flex-row mt-1">
            <div class="grow">Nazwisko</div>
            <div class="grow text-right">{{ user.user_profiles.recruiter_last_name ?? '-' }}</div>
        </div>
    </div>
</template>