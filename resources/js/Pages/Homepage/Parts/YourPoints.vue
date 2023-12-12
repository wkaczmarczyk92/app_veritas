<script setup>

import CountUp from 'vue-countup-v3';
import { ref } from 'vue';
import Header from '@/Components/Templates/Header.vue';

// import UserPointsBalance from '@/Modals/UserPointsBalance.vue';



import { level, levelColor } from '@/Components/Functions/Level.vue';

const props = defineProps({
    total_points: {
        type: Number,
        default: 0
    },
    user_next_checkpoint: {
        type: Number,
        required: true
    },
    user_prev_checkpoint: {
        type: Number,
        required: true
    },
    starting_position: {
        type: Number,
        required: true
    },
    username: {
        type: String,
        required: true
    },
    user_level: {
        type: Number,
        required: true
    },
    current_points: {
        type: [Number, null],
        required: true,
        default: 0
    },
    levels: {
        type: Object,
        required: true
    },
    // max_points: {
    //     type: Number,
    //     required: true
    // }
});

defineEmits([
    'open'
])

const _100_percent = props.max_points;
const user_points_to_fill = props.total_points;

const max = props.levels[3].checkpoints.checkpoint;
console.log(max);
// console.log(_100_percent);

const countHeight = (user_points, _100_percent) => {
    if (user_points >= _100_percent) {
        return 100;
    }

    return (100 * user_points_to_fill) / _100_percent;

}
const height = ref(countHeight(user_points_to_fill, _100_percent) - .5);

const from_top = 100 - height.value;
// const duration = ref(4);

const bg_color = () => {
    switch (props.user_level) {
        case 1:
            return '#996633';
        case 2:
            return '#C0C0C0';
        case 3:
        case 4:
            return '#DBC448';
    }
}
const background_color = ref(bg_color());
const prev_checkpoints = ref(0);

const show_arrow = () => {
    // console.log(user_points >= _100_percent);
    return user_points_to_fill >= _100_percent ? false : true;
}

</script>


<template>
    <div :style="`--height: ${height}`"></div>
    <section class="bg-gray-100 overflow-hidden shadow-xl rounded sm:rounded-lg px-6 pt-16 pb-8 relative">
        <Header
            h="1"
            value="Witaj"
            :center="true"    
        >
        </Header>
        <Header
            h="1"
            :value="`${username}!`"
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
        ></Header>

        <div class="curent-points text-center text-gray-800 text-2xl my-5 flex flex-row justify-center">
            <count-up class="mr-2 text-blue-700 font-bold" :duration="4" :start-val="0" :end-val="total_points"></count-up> punktów
        </div>



        <div class="flex justify-center mt-12">
            <div class="w-full sm:w-4/5 md:w-4/6 lg:w-1/2 svg-container">
                <img src="/images/svg/VER_group3.svg">
                <div class="points-counter flex flex-row justify-left" v-if="show_arrow()">
                    <i class="fa-sharp fa-light fa-left m-auto counter-icon text-gray-800"></i>
                    <p class="flex-row hidden md:flex text-gray-800">
                        <count-up :duration="4" :end-val="total_points" :start-val="0"></count-up> / {{ max }}
                    </p>
                    
                </div>
            </div>
        </div>
        <div class="w-full text-center mt-28 text-gray-800">
            <h2 class="font-bold text-2xl sm:text-3xl">
                Aktualny poziom: 
                <span
                    :class="levelColor(user_level)"
                    class="mr-2">
                        <b>
                            {{ level(levels, user_level).toUpperCase() }}
                        </b>
                </span>
            </h2>
            <h2 class="font-bold text-xl sm:text-2xl mt-6" v-if="user_level < 4">
                Kolejny poziom: 
                <span
                    :class="levelColor(user_level + 1)"
                    class="mr-2">
                        <b>
                            {{ level(levels, user_level + 1).toUpperCase() }}
                        </b>
                </span>
                - 
                <span>
                    {{ total_points ?? 0 }} / {{ user_next_checkpoint }}
                </span>
            </h2>
        </div>
        <div class="flex flex-col text-gray-800 mt-20 px-6">
            <div>
                <i class="fa-solid fa-coins"></i> Aktualny bilans punktów: {{ current_points ?? 0 }}
            </div>
            <p class="border-solid points-balance text-blue-700 hover:text-blue-900 hover:underline">
                <span @click="$emit('open', 'points_history')">
                    <i class="fa-sharp fa-regular fa-chart-line-up mr-2"></i> 
                    Bilans punktów
                </span>
            </p>
        </div>
    </section>
    
</template>


<style scoped>
    .svg-container::before {
        height: calc(v-bind(height + '%') - 1px);
        /* background-color: var(v-bind(background_color)); */
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