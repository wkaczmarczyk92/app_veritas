<script setup lang="ts">

import { ref } from 'vue';

import CountUp from 'vue-countup-v3';
import type { ICountUp, CountUpOptions } from 'vue-countup-v3'

import Header from '@/Components/Templates/Header.vue';
import { level, levelColor, neonColor } from '@/Components/Functions/Level.vue';
import { date_of_last_update, date_of_next_update } from '@/Components/Functions/DateFormat.vue';
import MButton from '@/Components/Buttons/MButton.vue';

import { useUserStore } from '@/Pinia/UserStore';
import { useModalStore } from '@/Pinia/ModalStore';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
});

const modalStore = useModalStore();

const options: CountUpOptions = {
    separator: ''
}

defineEmits([
    'open'
])

const last_points_insert_date = ref('??')

const userStore = useUserStore();
await userStore.set_user();

const get_last_insert_date = async () => {
    let date = await date_of_last_update()
    last_points_insert_date.value = date
}

get_last_insert_date()

const height = ref((userStore.user.user_profiles.total_points >= props.levels[3].checkpoints.checkpoint
    ? 100
    : ((100 * userStore.user.user_profiles.total_points) / props.levels[3].checkpoints.checkpoint)) - .5);

const from_top = 100 - height.value;
const bg_arr = {
    1: '#33cc33',
    2: '#996633',
    3: '#C0C0C0',
    4: '#DBC448',
};
const background_color = ref(bg_arr[userStore.user.user_profiles.level]);

</script>

<template>
    <div :style="`--height: ${height}`"></div>
    <v-card class="!tw-shadow-xl !tw-bg-gray-100">
        <template v-slot:title>
            <Header h="1" value="Witaj" :center="true" mb="mb-1">
            </Header>
            <Header h="1" :value="`${userStore.username()}!`" :center="true" color="tw-text-blue-700">
            </Header>
        </template>
        <v-card-text class="!tw-pb-0 !tw-px-0">
            <div class="tw-flex tw-flex-row tw-justify-center">
                <hr class="tw-w-1/12 tw-my-10 tw-border-2 tw-border-blue-700">
            </div>

            <Header h="1" :center="true" value="Łącznie zdobyłeś/aś:" icon="fa-solid fa-trophy-star"
                icon_color="tw-text-yellow-400" mb="tw-mb-2"></Header>

            <div
                class="tw-flex tw-flex-row tw-justify-center tw-mb-5 tw-text-2xl tw-text-center tw-text-gray-800 curent-points">
                <count-up class="tw-mr-2 tw-font-bold tw-text-blue-700" :duration="4" :options="options" :start-val="0"
                    :end-val="userStore.user.user_profiles.total_points"></count-up> punktów
            </div>

            <div class="tw-flex tw-justify-center tw-mt-12">
                <div class="tw-w-full sm:tw-w-4/5 md:tw-w-4/6 lg:tw-w-1/2 svg-container">
                    <img src="/images/svg/VER_group3.svg">
                    <div class="tw-flex tw-flex-row points-counter tw-justify-left"
                        v-if="!(userStore.user.user_profiles.total_points >= props.levels[3].checkpoints.checkpoint)">
                        <i class="tw-m-auto tw-text-gray-800 fa-solid fa-arrow-left-long counter-icon"></i>
                        <p class="tw-flex-row tw-hidden tw-text-gray-800 md:tw-flex">
                            <count-up :duration="4" :end-val="userStore.user.user_profiles.total_points"
                                :options="options" :start-val="0"></count-up> / {{ levels[3].checkpoints.checkpoint }}
                        </p>

                    </div>
                </div>
            </div>
            <div class="tw-bg-gray-900 tw-mt-20">
                <div class="tw-w-full tw-px-6 tw-py-20 tw-text-center tw-mt-28">
                    <Header h="2" :center="true" value="Aktualny poziom" icon="fa-solid fa-ranking-star"
                        icon_color="tw-text-yellow-400" mb="tw-mb-8" color="tw-text-gray-100"></Header>
                    <MButton bg="tw-bg-transparent"
                        :value="level(levels, userStore.user.user_profiles.level).toUpperCase()"
                        color="tw-text-green-400"
                        add_class="sm:tw-px-24 tw-py-6 tw-rounded-2xl neon-after-btn-green tw-border-green-400 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative"
                        :neon_color="neonColor(userStore.user.user_profiles.level)"></MButton>

                    <h2 class="tw-mt-6 tw-text-xl tw-font-bold tw-text-gray-100 sm:tw-text-2xl"
                        v-if="userStore.user.user_profiles.level < 4">
                        Kolejny poziom:
                        <span :class="levelColor(userStore.user.user_profiles.level + 1)" class="tw-mr-2 tw-font-bold">
                            {{ level(levels, userStore.user.user_profiles.level + 1).toUpperCase() }}
                        </span>
                        -
                        <span>
                            {{ userStore.user.user_profiles.total_points ?? 0 }} / {{
                                levels[(userStore.user.user_profiles.level
                                    < 3 ? userStore.user.user_profiles.level : 3)].checkpoints.checkpoint }} </span>
                    </h2>
                    <div
                        class="tw-flex tw-flex-col tw-px-6 tw-mt-10 tw-text-lg tw-text-left tw-text-gray-100 tw-justify-left">
                        <div>
                            <i class="tw-mr-2 fa-solid fa-coins"></i>
                            Łączny bilans punktów: {{ userStore.user.user_profiles.current_points ?? 0 }}<span
                                class="tw-text-red-600">*</span>
                        </div>
                        <p
                            class="tw-text-orange-400 tw-border-solid points-balance hover:tw-text-orange-700 hover:tw-underline">
                            <span @click="modalStore.visibility.points_history = true">
                                <i class="tw-mr-2 fa-sharp fa-regular fa-chart-line-up"></i>
                                Bilans punktów
                            </span>
                        </p>
                        <p class="tw-mt-4 tw-text-sm"><span class="tw-text-red-600">*</span>Stan konta na -
                            <span class="tw-font-bold tw-text-orange-400">{{ last_points_insert_date }}</span>. Kolejna
                            przewidywana
                            aktualizacja - <span class="tw-font-bold tw-text-orange-400">{{ date_of_next_update()
                            }}</span>
                            (punkty
                            naliczane są od <span class="tw-font-bold tw-text-orange-400">2023-01-01</span>)
                        </p>
                    </div>
                </div>
            </div>
        </v-card-text>
    </v-card>
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
