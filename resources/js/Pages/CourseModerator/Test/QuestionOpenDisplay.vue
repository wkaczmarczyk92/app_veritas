<template>
    <div class="tw-flex tw-flex-col tw-p-4">
        <p class="tw-text-xs tw-italic">Treść pytania:</p>
        <p class="tw-text-lg">{{ question.question }}</p>
    </div>
</template>

<script setup>

const props = defineProps({
    question: Object
})

</script>