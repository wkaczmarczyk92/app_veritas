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
    <a 
        :href="url === null ? '#' : url" 
        class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200" 
        :class="url === null ? 'hover:cursor-not-allowed' : ''">
            {{ value }}
    </a>
</template>