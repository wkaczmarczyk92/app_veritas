<script setup>

defineProps({
    current_page: {
        type: Number,
        required: true
    },
    last_page: {
        type: Number,
        required: true
    }
});

</script>

<template>
    <!-- <a href="#" class="disabled-paginatio-item"> -->
    <v-btn variant="tonal" class="tw-shadow-lg" disabled color="#030712" id="disabled-pagination-btn">
        {{ current_page }} z {{ last_page }}
    </v-btn>
    <!-- </a> -->
</template>

<style>
#disabled-pagination-btn {
    opacity: .9;
}
</style>
