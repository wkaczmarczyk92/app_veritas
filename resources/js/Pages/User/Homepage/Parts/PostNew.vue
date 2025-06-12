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
    3: {
        bg: 'tw-from-blue-300 tw-via-indigo-700 tw-to-blue-700',
        bg_new: 'tw-bg-indigo-600',
        icon: 'fa-solid fa-ranking-star',
        label: 'konkurs'
    },
    2: {
        bg: 'tw-from-lime-300 tw-via-green-700 tw-to-lime-700',
        bg_new: 'tw-bg-blue-600',
        icon: 'fa-sharp fa-solid fa-money-check-dollar',
        label: 'premia'
    },
    1: {
        bg: 'tw-from-red-300 tw-via-pink-700 tw-to-red-700',
        bg_new: 'tw-bg-green-600',
        icon: 'fa-solid fa-gift',
        label: 'bonus'
    },
    4: {
        bg: 'tw-from-orange-300 tw-via-amber-700 tw-to-orange-700',
        bg_new: 'tw-bg-orange-600',
        icon: 'fa-duotone fa-newspaper',
        label: 'aktualności'
    },
})

// const current = ref(bg.find((item) => {
//     return item.label == props.post.post_labels.name;
// }))

// console.log(current.value);

const color_change_from = ref('rgb(0, 102, 204)');
const color_change_to = ref('#1d4ed8');

</script>


<template>
    <v-card class="!tw-shadow-2xl !tw-bg-gray-100 !tw-mt-10">
        <div class="tw-absolute tw-top-0 tw-right-0 tw-px-2 tw-py-2 tw-font-bold tw-text-gray-100"
            :class="`${bg[post.post_labels.id].bg_new}`">
            {{ post.post_labels.name.toUpperCase() }}
        </div>
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-text-2xl">
                <div>
                    <i :class="bg[post.post_labels.id].icon"></i>
                </div>
                <div>
                    {{ post.title }}
                </div>
            </div>
        </template>
        <v-card-text class="!tw-p-10">
            <div>
                <div class="post__body" v-html="post.body.replaceAll(color_change_from, color_change_to)"></div>
            </div>
        </v-card-text>
    </v-card>
</template>
