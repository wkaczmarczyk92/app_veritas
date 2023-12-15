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
    <section class="bg-gray-100 overflow-hidden shadow-xl rounded sm:rounded-lg pt-16 relative">
        <Header
            h="1"
            value="Witaj"
            :center="true"    
            mb="mb-1"
        >
        </Header>
        <Header
            h="1"
            :value="`${user.user_profiles.first_name} ${user.user_profiles.last_name}!`"
            :center="true"   
            color="text-blue-700" 
        >
        </Header>

        <div class="flex flex-row justify-center">
            <hr class="my-10 w-1/12 border-blue-700 border-2">
        </div>

        <Header
            h="1"
            :center="true"
            value="Łącznie zdobyłeś/aś:"
            icon="fa-solid fa-trophy-star"
            icon_color="text-yellow-400"
            mb="mb-2"
        ></Header>

        <div class="curent-points text-center text-gray-800 text-2xl mb-5 flex flex-row justify-center">
            <count-up class="mr-2 text-blue-700 font-bold" :duration="4" :options="options" :start-val="0" :end-val="user.user_profiles.total_points"></count-up> punktów
        </div>

        <div class="flex justify-center mt-12">
            <div class="w-full sm:w-4/5 md:w-4/6 lg:w-1/2 svg-container">
                <img src="/images/svg/VER_group3.svg">
                <div class="points-counter flex flex-row justify-left" v-if="!(props.user.user_profiles.total_points >= props.levels[3].checkpoints.checkpoint)">
                    <i class="fa-solid fa-arrow-left-long m-auto counter-icon text-gray-800"></i>
                    <!-- <i class="fa-sharp fa-light fa-left"></i> -->
                    <p class="flex-row hidden md:flex text-gray-800">
                        <count-up :duration="4" :end-val="user.user_profiles.total_points" :options="options" :start-val="0"></count-up> / {{ levels[3].checkpoints.checkpoint }}
                    </p>
                    
                </div>
            </div>
        </div>
        <div class="w-full text-center mt-28 text-gray-800 bg-gray-800 px-6 py-20">
            <Header
                h="2"
                :center="true"
                value="Aktualny poziom"
                icon="fa-solid fa-ranking-star"
                icon_color="text-yellow-400"
                mb="mb-8"
                color="text-gray-100"
            ></Header>
            <MButton
                bg="bg-transparent"
                :value="level(levels, user.user_profiles.level).toUpperCase()"
                color="text-green-400"
                add_class="px-6 sm:px-24 py-6 rounded-2xl neon-after-btn-green border-green-400 font-bold text-lg sm:text-2xl relative"
                :neon_color="neonColor(user.user_profiles.level)"
            ></MButton>
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
            <!-- <h2 class="font-bold text-2xl sm:text-3xl">
                Aktualny poziom: 
                <span
                    :class="levelColor(3)"
                    class="mr-2 font-bold">
                        {{ level(levels, user.user_profiles.level).toUpperCase() }}
                </span>
            </h2> -->
            <h2 class="font-bold text-xl sm:text-2xl mt-6 text-gray-100" v-if="user.user_profiles.level < 4">
                Kolejny poziom: 
                <span
                    :class="levelColor(user.user_profiles.level + 1)"
                    class="mr-2 font-bold">
                        {{ level(levels, user.user_profiles.level + 1).toUpperCase() }}
                </span>
                - 
                <span>
                    {{ user.user_profiles.total_points ?? 0 }} / {{ levels[(user.user_profiles.level < 3 ? user.user_profiles.level : 3)].checkpoints.checkpoint }}
                </span>
            </h2>
            <div class="flex flex-col text-gray-100 mt-10 px-6 justify-left text-left text-lg">
                <div>
                    <i class="fa-solid fa-coins mr-2"></i>
                    Łączny bilans punktów: {{ user.user_profiles.current_points ?? 0 }}<span class="text-red-600">*</span>
                </div>
                <p class="border-solid points-balance text-orange-400 hover:text-orange-700 hover:underline">
                    <span @click="$emit('open', 'points_history')">
                        <i class="fa-sharp fa-regular fa-chart-line-up mr-2"></i> 
                        Bilans punktów
                    </span>
                </p>
                <p class="text-sm mt-4"><span class="text-red-600">*</span>Stan konta na - 
                    <span class="text-orange-400 font-bold">{{ last_points_insert_date }}</span>. Kolejna przewidywana aktualizacja - <span class="text-orange-400 font-bold">{{ date_of_next_update() }}</span> (punkty naliczane są od <span class="text-orange-400 font-bold">2023-01-01</span>)</p>
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