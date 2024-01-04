<template>
    <v-card variant="tonal" :color="'white'" class="tw-shadow-2xl">
        <v-card-title class="tw-text-gray-800 hover:tw-underline hover:tw-text-blue-700">
            <a :href="route('pointbreakpoints.index')" class="tw-text-lg tw-ont-bold">
                Bonus za wejście na poziom:
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        </v-card-title>
        <v-card-text>
            <div class="tw-flex tw-flex-row tw-text-sm hover:tw-bg-gray-300" v-for="(item, index) in levels">
                <div class="tw-grow">
                    <span :class="levelColor(item.id)"><b>{{ level(levels,
                        item.id).toUpperCase() }}</b>
                    </span>
                </div>
                <div class="tw-text-right tw-text-gray-800 tw-grow">
                    {{ item.bonus_value.value }} €
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script setup>

import { level, levelColor } from '@/Components/Functions/Level.vue';

defineProps({
    levels: {
        type: Object,
        required: true
    }
})

</script>
