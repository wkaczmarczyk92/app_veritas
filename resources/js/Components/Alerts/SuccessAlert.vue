<script setup>

defineProps({
    class: {
        type: String,
        default: ''
    }
})

</script>


<template>
    <div class="tw-text-green-600" :class="class">
        <slot></slot>
    </div>
</template>
