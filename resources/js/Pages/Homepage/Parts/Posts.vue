<script setup>

import { ref } from 'vue';

const props = defineProps({
    class: {
        type: String
    },
    title: {
        type: String
    },
    body: {
        type: String
    },
    label: {
        type: String
    },
    post: {
        type: Object
    }
});

// console.log(props.post);

const bg = ref({
    konkurs: {
        bg: 'from-blue-300 via-indigo-700 to-blue-700',
        icon: 'fa-solid fa-ranking-star'
    },
    premia: {
        bg: 'from-lime-300 via-green-700 to-lime-700',
        icon: 'fa-sharp fa-solid fa-money-check-dollar'
    },
    bonus: {
        bg: 'from-red-300 via-pink-700 to-red-700',
        icon: 'fa-solid fa-gift'
    }
})

const color_change_from = ref('rgb(0, 102, 204)');
const color_change_to = ref('#1d4ed8');

</script>


<template>
    <div class="post bg-gradient-to-tl from-blue-100 via-gray-200 to-gray-300 px-10 py-12 rounded-xl shadow-2xl" :class="class">     
        <div class="post__type bg-gradient-to-br" :class="bg[label].bg">
            <div class="post__type__text" :class="label == 'konkurs' ? 'post__type__competition' : ''">{{ label.toUpperCase() }}</div>
        </div>
        <div class="post__title text-center text-2xl sm:text-3xl font-bold text-gray-800 text-gradient mt-8">
            <i :class="bg[label].icon"></i>
            {{ title }}
        </div>
        <!-- <div>START: {{ post.start_at }}</div>
        <div>Koniec: {{ post.end_at }}</div> -->
        <div class="post__body indent-4 text-gray-700 mt-6" v-html="body.replaceAll(color_change_from, color_change_to)">
        </div>
    </div>

</template>