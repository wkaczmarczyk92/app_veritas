<script setup>

defineProps({
    url: {
        type: [String, null],
        required: true
    },
    value: {
        type: String,
        default: 'Wstecz'
    }
});

</script>

<template>
    <a :href="url === null ? '#' : url" class="tw-relative tw-block"
        :class="url === null ? 'hover:tw-cursor-not-allowed' : ''">
        <v-btn variant="tonal" class="tw-shadow-lg" color="#ef4444">
            {{ value }}
        </v-btn>
    </a>
</template>
