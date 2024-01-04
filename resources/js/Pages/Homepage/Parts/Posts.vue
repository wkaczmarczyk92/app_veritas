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
        bg: 'tw-from-blue-300 tw-via-indigo-700 tw-to-blue-700',
        icon: 'fa-solid fa-ranking-star'
    },
    premia: {
        bg: 'tw-from-lime-300 tw-via-green-700 tw-to-lime-700',
        icon: 'fa-sharp fa-solid fa-money-check-dollar'
    },
    bonus: {
        bg: 'tw-from-red-300 tw-via-pink-700 tw-to-red-700',
        icon: 'fa-solid fa-gift'
    }
})

const color_change_from = ref('rgb(0, 102, 204)');
const color_change_to = ref('#1d4ed8');

</script>


<template>
    <div class="post tw-bg-gradient-to-tl tw-from-blue-100 tw-via-gray-200 tw-to-gray-300 tw-px-10 tw-py-12 tw-rounded-xl tw-shadow-2xl"
        :class="class">
        <div class="post__type tw-bg-gradient-to-br" :class="bg[label].bg">
            <div class="post__type__text" :class="label == 'konkurs' ? 'post__type__competition' : ''">{{
                label.toUpperCase() }}</div>
        </div>
        <div
            class="post__title tw-text-center tw-text-2xl sm:tw-text-3xl tw-font-bold tw-text-gray-800 tw-text-gradient tw-mt-8">
            <i :class="bg[label].icon"></i>
            {{ title }}
        </div>
        <!-- <div>START: {{ post.start_at }}</div>
        <div>Koniec: {{ post.end_at }}</div> -->
        <div class="post__body tw-indent-4 tw-text-gray-700 tw-mt-6"
            v-html="body.replaceAll(color_change_from, color_change_to)">
        </div>
    </div>
</template>
