<script setup>

import { router } from '@inertiajs/vue3';
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
    // point_chekpoints: {
    //     type: Object
    // }
})

console.log(props.levels);

</script>

<template>
    <div class="bg-gray-100 shadow-xl rounded p-10">
        <!-- <h2 class="font-semibold text-xl leading-tight mb-4">
            Ustawienia
        </h2>
        <hr>
        <h2 class="font-semibold text-md leading-tight mt-8 mb-4">
            Mnożniki: <span class="edit-icon"><i class="fa-solid fa-pen-to-square" @click="router.visit(route('multiplier_settings'))"></i></span>
        </h2>

        <div class="flex flex-row" v-for="(item, index) in levels">
            <div class="grow">
                <span
                    :class="levelColor(item.id)"><b>{{ level(levels,
                        item.id).toUpperCase() }}</b>
                </span>
            </div>
            <div class="grow text-right">
                dzień x {{ item.multiplier.multiplier_value }}
            </div>
        </div>
        <hr class="mt-4">
        <h2 class="font-semibold text-md leading-tight mt-8 mb-4">
            Punkty i kwota wypłaty: <span class="edit-icon"><i class="fa-solid fa-pen-to-square" @click="router.visit(route('settings.index'))"></i></span>
        </h2>

        <div class="flex flex-row">
            <div class="grow">
                Minimalna ilość punktów do wypłaty
            </div>
            <div class="grow text-right"><b>{{ points_to_payout }}</b></div>
        </div>
        <div class="flex flex-row">
            <div class="grow">
                Kwota do wypłaty za <span class="text-main"><b>{{ points_to_payout }}</b></span> punktów
            </div>
            <div class="grow text-right"><b>{{ payout_cash }} €</b></div>
        </div>
        <hr class="mt-4"> -->
        <h2 class="font-semibold text-md leading-tight mt-8 mb-4">
            Punkty kontrolne: <span class="edit-icon"><i class="fa-solid fa-pen-to-square" @click="router.visit(route('pointbreakpoints.index'))"></i></span>
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