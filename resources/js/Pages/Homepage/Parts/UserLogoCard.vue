<script setup lang="ts">

import { ref } from 'vue';

import CountUp from 'vue-countup-v3';
import type { ICountUp, CountUpOptions } from 'vue-countup-v3'

import Header from '@/Components/Templates/Header.vue';
import { level, levelColor, neonColor } from '@/Components/Functions/Level.vue';
import { date_of_last_update, date_of_next_update } from '@/Components/Functions/DateFormat.vue';
import MButton from '@/Components/Buttons/MButton.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    levels: {
        type: Object,
        required: true
    }
});

console.log(props.levels)

const options: CountUpOptions = {
    separator: ''
}

defineEmits([
    'open'
])

const height = ref((props.user.user_profiles.total_points >= props.levels[3].checkpoints.checkpoint
    ? 100
    : ((100 * props.user.user_profiles.total_points) / props.levels[3].checkpoints.checkpoint)) - .5);

const from_top = 100 - height.value;
const bg_arr = {
    1: '#33cc33',
    2: '#996633',
    3: '#C0C0C0',
    4: '#DBC448',
};
const background_color = ref(bg_arr[props.user.user_profiles.level]);

const last_points_insert_date = ref('??')

const get_last_insert_date = async () => {
    let date = await date_of_last_update()
    last_points_insert_date.value = date
}

get_last_insert_date()

</script>


<template>
    <div :style="`--height: ${height}`"></div>
    <section class="tw-relative tw-pt-16 tw-overflow-hidden tw-bg-gray-100 tw-rounded tw-shadow-xl sm:tw-rounded-lg">
        <Header h="1" value="Witaj" :center="true" mb="mb-1">
        </Header>
        <Header h="1" :value="`${user.user_profiles.first_name} ${user.user_profiles.last_name}!`" :center="true"
            color="tw-text-blue-700">
        </Header>

        <div class="tw-flex tw-flex-row tw-justify-center">
            <hr class="tw-w-1/12 tw-my-10 tw-border-2 tw-border-blue-700">
        </div>

        <Header h="1" :center="true" value="Łącznie zdobyłeś/aś:" icon="fa-solid fa-trophy-star"
            icon_color="tw-text-yellow-400" mb="tw-mb-2"></Header>

        <div
            class="tw-flex tw-flex-row tw-justify-center tw-mb-5 tw-text-2xl tw-text-center tw-text-gray-800 curent-points">
            <count-up class="tw-mr-2 tw-font-bold tw-text-blue-700" :duration="4" :options="options" :start-val="0"
                :end-val="user.user_profiles.total_points"></count-up> punktów
        </div>

        <div class="tw-flex tw-justify-center tw-mt-12">
            <div class="tw-w-full sm:tw-w-4/5 md:tw-w-4/6 lg:tw-w-1/2 svg-container">
                <img src="/images/svg/VER_group3.svg">
                <div class="tw-flex tw-flex-row points-counter tw-justify-left"
                    v-if="!(props.user.user_profiles.total_points >= props.levels[3].checkpoints.checkpoint)">
                    <i class="tw-m-auto tw-text-gray-800 fa-solid fa-arrow-left-long counter-icon"></i>
                    <p class="tw-flex-row tw-hidden tw-text-gray-800 md:tw-flex">
                        <count-up :duration="4" :end-val="user.user_profiles.total_points" :options="options"
                            :start-val="0"></count-up> / {{ levels[3].checkpoints.checkpoint }}
                    </p>

                </div>
            </div>
        </div>
        <div class="tw-w-full tw-px-6 tw-py-20 tw-text-center tw-text-gray-800 tw-bg-gray-800 tw-mt-28">
            <Header h="2" :center="true" value="Aktualny poziom" icon="fa-solid fa-ranking-star"
                icon_color="tw-text-yellow-400" mb="tw-mb-8" color="tw-text-gray-100"></Header>
            <MButton bg="tw-bg-transparent" :value="level(levels, user.user_profiles.level).toUpperCase()"
                color="tw-text-green-400"
                add_class="sm:tw-px-24 tw-py-6 tw-rounded-2xl neon-after-btn-green tw-border-green-400 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative"
                :neon_color="neonColor(user.user_profiles.level)"></MButton>

            <!-- <v-btn variant="outlined" size="x-large" height="80px" width="300px"
                :class="`relative text-lg font-bold border-green-400 text-green-400 rounded-2xl neon-after-btn-green ${neonColor(user.user_profiles.level)}`">
                <span class="sm:text-2xl">{{ level(levels, user.user_profiles.level).toUpperCase() }}</span>
            </v-btn> -->
            <!-- <br> -->
            <!-- <MButton
                icon="fa-solid fa-ranking-star"
                bg="bg-transparent"
                :value="`Aktualny poziom: `"
                color="text-green-400"
                add_class="px-6 sm:px-24 py-6 rounded-2xl neon-after-btn-green border-green-400 font-bold text-lg sm:text-2xl relative my-10"
                :subheader="level(levels, user.user_profiles.level).toUpperCase()"
                :neon_color="neonColor(user.user_profiles.level)"
            ></MButton> -->
            <!-- <h2 class="text-2xl font-bold sm:text-3xl">
                Aktualny poziom:
                <span
                    :class="levelColor(3)"
                    class="mr-2 font-bold">
                        {{ level(levels, user.user_profiles.level).toUpperCase() }}
                </span>
            </h2> -->
            <h2 class="tw-mt-6 tw-text-xl tw-font-bold tw-text-gray-100 sm:tw-text-2xl" v-if="user.user_profiles.level < 4">
                Kolejny poziom:
                <span :class="levelColor(user.user_profiles.level + 1)" class="tw-mr-2 tw-font-bold">
                    {{ level(levels, user.user_profiles.level + 1).toUpperCase() }}
                </span>
                -
                <span>
                    {{ user.user_profiles.total_points ?? 0 }} / {{ levels[(user.user_profiles.level < 3 ?
                        user.user_profiles.level : 3)].checkpoints.checkpoint }} </span>
            </h2>
            <div class="tw-flex tw-flex-col tw-px-6 tw-mt-10 tw-text-lg tw-text-left tw-text-gray-100 tw-justify-left">
                <div>
                    <i class="tw-mr-2 fa-solid fa-coins"></i>
                    Łączny bilans punktów: {{ user.user_profiles.current_points ?? 0 }}<span
                        class="tw-text-red-600">*</span>
                </div>
                <p class="tw-text-orange-400 tw-border-solid points-balance hover:tw-text-orange-700 hover:tw-underline">
                    <span @click="$emit('open', 'points_history')">
                        <i class="tw-mr-2 fa-sharp fa-regular fa-chart-line-up"></i>
                        Bilans punktów
                    </span>
                </p>
                <p class="tw-mt-4 tw-text-sm"><span class="tw-text-red-600">*</span>Stan konta na -
                    <span class="tw-font-bold tw-text-orange-400">{{ last_points_insert_date }}</span>. Kolejna przewidywana
                    aktualizacja - <span class="tw-font-bold tw-text-orange-400">{{ date_of_next_update() }}</span> (punkty
                    naliczane są od <span class="tw-font-bold tw-text-orange-400">2023-01-01</span>)
                </p>
            </div>
        </div>

    </section>
</template>

<style>
.svg-container::before {
    height: calc(v-bind(height + '%') - 1px);
    background-color: v-bind(background_color);
}

@keyframes backgroundFill {
    from {
        height: 0;
    }

    to {
        height: v-bind(height + '%');
    }
}

.points-counter {
    top: v-bind(from_top + '%');
}

@keyframes counterGrow {
    from {
        top: 100%;
    }

    to {
        top: v-bind(from_top + '%');
    }
}
</style>
