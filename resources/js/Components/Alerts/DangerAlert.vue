<script setup>

defineProps({
    class: {
        type: String,
        default: ''
    }
})

</script>


<template>
    <div class="text-red-600" :class="class">
        <slot></slot>
    </div>
</template>