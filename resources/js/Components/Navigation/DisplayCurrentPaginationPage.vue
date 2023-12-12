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
    <a 
        href="#" 
        class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 disabled-paginatio-item">
            {{ current_page }} z {{ last_page }}
    </a>
</template>