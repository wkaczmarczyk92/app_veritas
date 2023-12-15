<template>
    <h2 class="font-semibold text-md leading-tight mb-2">
        Punkty kontrolne: 
        <span class="edit-icon">
            <a :href="route('pointbreakpoints.index')">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        </span>
    </h2>
    <div class="flex flex-row text-sm hover:bg-gray-300" v-for="(item, index) in levels">
        <div class="grow">
            <span
                :class="levelColor(item.id)"><b>{{ level(levels,
                    item.id).toUpperCase() }}</b>
            </span>
        </div>
        <div class="grow text-right">
            {{ item.checkpoints.checkpoint }} pkt
        </div>
    </div>
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