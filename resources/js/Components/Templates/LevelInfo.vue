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
        <div class="tw-border-2 tw-border-blue-600 tw-rounded-xl tw-text-white tw-py-6 tw-px-3 tw-text-center tw-relative">
            <!-- <div class="relative">
                <Tooltip v-if="tooltip" class="absolute bg-sky-700" :style="`right: -100%;`" :top="48" :left="false" :translateX="true">Bonus został wypłacony</Tooltip>
                <i v-if="current?.completed && (index + 1) != 1" class="fa-solid fa-circle-check bonus-payout-compelted-icon text-green-600 hover:cursor-pointer" @mouseover="tooltip = true" @mouseleave="tooltip = false"></i>
            </div> -->
            <p class="tw-text-white tw-mb-0 tw-font-bold">POZIOM:</p>
            <p class="tw-mt-0 tw-text-blue-500">
                <span :class="levelColor(index + 1)"><b>{{ level(levels,
                    index + 1).toUpperCase() }}</b></span>
            </p>
            <div class="tw-flex tw-flex-row tw-justify-center">
                <hr class="tw-my-4 tw-w-1/2 tw-border-blue-500">
            </div>
            <div>Kwota za poziom - <span class="tw-text-blue-600 tw-font-bold tw-text-xl">{{ levels[index].bonus_value.value
            }}
                    €</span></div>
            <div class="tw-mt-2" v-if="index > 0"
                :class="user.user_profiles.total_points >= levels[index].checkpoints.checkpoint ? 'te-text-green-500' : ''">
                <span
                    :class="user.user_profiles.total_points >= levels[index].checkpoints.checkpoint ? '' : 'te-text-blue-500'">
                    {{ user.user_profiles.total_points >= levels[index].checkpoints.checkpoint ?
                        levels[index].checkpoints.checkpoint : user.user_profiles.total_points }}
                </span> / {{ levels[index].checkpoints.checkpoint }}
            </div>
            <div v-else class="tw-mt-2 tw-text-green-500">Poziom początkowy</div>
            <div class="tw-mt-4">
                <div v-if="index == 0">
                    <p>Brak bonusu</p>
                </div>
                <div v-else class="tw-flex tw-flex-row tw-justify-center tw-place-items-center tw-gap-2">
                    <p>Bonus wypłacono</p>
                    <i v-if="current?.completed" class="fa-solid fa-circle-check tw-text-green-500"></i>
                    <i v-else class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
            <div class="tw-mt-5">
                <i v-if="user.user_profiles.total_points >= levels[index].checkpoints.checkpoint"
                    class="fa-solid fa-thumbs-up tw-text-green-500 tw-text-3xl"></i>
                <i v-else class="fa-sharp fa-solid fa-hourglass-clock tw-text-3xl tw-text-orange-500"></i>
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
