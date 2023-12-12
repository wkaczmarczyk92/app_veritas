<script setup>

import { level, levelColor } from '@/Components/Functions/Level.vue';
import { ref } from 'vue';
import Tooltip from './Tooltip.vue';

const props = defineProps({
    levels: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true
    },
    index: {
        type: Number,
        defualt: 0
    }
})

const get_current = () => {
    let x = props.user.user_has_bonus.filter(item => {
        return item.level_id == (props.index + 1);
    });

    return x[0]
}

const current = ref(get_current());

// console.log(props.user.user_has_bonus);
console.log(props.index, 'index');
console.log(current.value, 'value');

const tooltip = ref(false)

</script>

<template>
   <div class="">
        <div class="border-2 border-blue-600 rounded-xl text-white py-6 px-3 text-center relative">
            <!-- <div class="relative">
                <Tooltip v-if="tooltip" class="absolute bg-sky-700" :style="`right: -100%;`" :top="48" :left="false" :translateX="true">Bonus został wypłacony</Tooltip>
                <i v-if="current?.completed && (index + 1) != 1" class="fa-solid fa-circle-check bonus-payout-compelted-icon text-green-600 hover:cursor-pointer" @mouseover="tooltip = true" @mouseleave="tooltip = false"></i>
            </div> -->
            <p class="text-white mb-0 font-bold">POZIOM:</p>
            <p class="mt-0 text-blue-500">
                <span
                    :class="levelColor(index + 1)"><b>{{ level(levels,
                        index + 1).toUpperCase() }}</b></span>
            </p>
            <div class="flex flex-row justify-center">
                <hr class="my-4 w-1/2 border-blue-500">
            </div>
            <div>Kwota za poziom - <span class="text-blue-600 font-bold text-xl">{{ levels[index].bonus_value.value }} €</span></div>
            <div class="mt-2" v-if="index > 0"
                :class="user.user_profiles.total_points >= levels[index].checkpoints.checkpoint ? 'text-green-500' : ''">
                    <span :class="user.user_profiles.total_points >= levels[index].checkpoints.checkpoint ? '' : 'text-blue-500'">
                        {{ user.user_profiles.total_points >= levels[index].checkpoints.checkpoint ? levels[index].checkpoints.checkpoint : user.user_profiles.total_points }}
                    </span> / {{ levels[index].checkpoints.checkpoint }}
            </div>
            <div v-else class="mt-2 text-green-500">Poziom początkowy</div>
            <div class="mt-4">
                <div v-if="index == 0">
                    <p>Brak bonusu</p>
                </div>
                <div v-else class="flex flex-row justify-center place-items-center gap-2">
                    <p>Bonus wypłacono</p>
                    <i v-if="current?.completed" class="fa-solid fa-circle-check text-green-500"></i>
                    <i v-else class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
            <div class="mt-5">
                <i v-if="user.user_profiles.total_points >= levels[index].checkpoints.checkpoint" class="fa-solid fa-thumbs-up text-green-500 text-3xl"></i>
                <i v-else class="fa-sharp fa-solid fa-hourglass-clock text-3xl text-orange-500"></i>
            </div>
        </div>
    </div>
</template>

<style>

.bonus-payout-compelted-icon {
    position: absolute;
    top: -10px;
    right: -10px;
}

</style>