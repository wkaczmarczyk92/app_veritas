<template>
    <v-chip variant="outlined" class="" size="small">
        <span v-if="question_types !== null">
            {{ question_types.filter(q => q.id === question.type_id)[0].name_pl }}
        </span>
        <span v-else>{{ question.type.name_pl }}</span>
    </v-chip>
</template>

<script setup>

defineProps({
    question: Object,
    question_types: {
        type: [null, Array],
        default: null
    }
})

</script>