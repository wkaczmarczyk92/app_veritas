<script setup>

import { level, levelColor } from '@/Components/Functions/Level.vue';

const props = defineProps({
    levels: {
        type: Object
    },
    payout_cash: {
        type: Number
    },
    points_to_payout: {
        type: Number
    },
})

</script>

<template>
    <div class="bg-gray-100 shadow-xl rounded p-10">
        <h2 class="font-semibold text-md leading-tight mt-8 mb-4">
            Punkty kontrolne: 
            <span class="edit-icon">
                <a :href="route('pointbreakpoints.index')">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </span>
        </h2>
        <div class="flex flex-row" v-for="(item, index) in levels">
            <div class="grow">
                <span
                    :class="levelColor(item.id)"><b>{{ level(levels,
                        item.id).toUpperCase() }}</b>
                </span>
            </div>
            <div class="grow text-right">
                {{ item.checkpoints.checkpoint }} punktów
            </div>
        </div>
        <hr class="mt-4">
        <h2 class="font-semibold text-md leading-tight mt-8 mb-4">
            Bonus za wejście na poziom: <span class="edit-icon"><i class="fa-solid fa-pen-to-square" @click="router.visit(route('level.bonus.value.index'))"></i></span>
        </h2>
        <div class="flex flex-row" v-for="(item, index) in levels">
            <div class="grow">
                <span
                    :class="levelColor(item.id)"><b>{{ level(levels,
                        item.id).toUpperCase() }}</b>
                </span>
            </div>
            <div class="grow text-right">
                {{ item.bonus_value.value }} €
            </div>
        </div>

    </div>
</template>