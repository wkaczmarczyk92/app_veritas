<template>
    <span :class="visibility.id == 1 ? 'tw-text-orange-600' : 'tw-text-green-600'">
        {{ visibility.name_pl }}
    </span>
</template>

<script setup>

defineProps({
    visibility: Object
})

</script>