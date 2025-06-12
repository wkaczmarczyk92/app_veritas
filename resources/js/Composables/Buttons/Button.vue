<template>
    <v-btn :variant="variant" :color="_color(color)" size="large">
        <template v-slot:prepend>
            <slot name="icon" />
        </template>

        {{ value }}
    </v-btn>
</template>

<script setup lang="ts">

defineProps({
    value: {
        type: String,
        default: 'Wyślij'
    },
    variant: {
        type: [String],
        default: 'outlined'
    },
    color: {
        type: String,
        default: 'green'
    }
})

const _color = (color) => {
    switch (color) {
        case 'green':
            return '#16a34a'
        case 'red':
            return '#dc2626'
        case 'rose':
            return '#e11d48'
        case 'blue':
            return '#2563eb'
        default:
            return color
    }
}

console.log(23123123)

</script>
