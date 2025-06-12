<template>
    <Tile :title="file.name">
        <!-- <div>
            <p>Nazwa pliku: {{ file.name }}</p>
            <p>Typ MIME pliku: {{ file.mime_type.type }}</p>
            <p>Rozszerzenie pliku: .{{ file.extension }}</p>
            <p class="tw-mb-4">HASH pliku: {{ file.hash ?? 'brak' }}</p>
        </div> -->
        <div class="media-library-image tw-text-center tw-flex tw-justify-center tw-items-center">
            <img :src="file.path">
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