<template>
    <v-select variant="outlined" :error-messages="error" :hide-details="error ? false : true" />
</template>

<script setup>

defineProps({
    error: {
        type: [null, String],
        default: null
    }
})

</script>