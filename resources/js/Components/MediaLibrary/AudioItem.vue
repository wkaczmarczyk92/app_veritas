<template>
    <Tile :title="file.name">
        <div class="media-library-image tw-text-center tw-flex tw-flex-col tw-justify-center tw-items-center">
            <div>
                <i class="fa-solid fa-volume tw-text-4xl tw-text-blue-500"></i>
            </div>
            <div>
                {{ file.name }}
            </div>
        </div>
    </Tile>
</template>

<script setup>

import Tile from './Tile.vue';

const props = defineProps({
    file: {
        type: Object,
        required: true
    }
})

</script>

<style>
.media-library-image {
    display: inline-block;
}

.media-library-image img {
    max-height: 200px;
    width: auto;
    height: auto;
    display: inline-block;
}
</style>