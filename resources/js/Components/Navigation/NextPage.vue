<script setup>

defineProps({
    url: {
        type: [String, null],
        required: true
    },
    value: {
        type: String,
        default: 'Dalej'
    }
});

</script>

<template>
    <a :href="url === null ? '#' : url" class="tw-relative tw-block"
        :class="url === null ? 'hover:tw-cursor-not-allowed' : ''">
        <v-btn variant="tonal" class="tw-shadow-lg" color="#3b82f6">
            {{ value }}
        </v-btn>
    </a>
</template>
