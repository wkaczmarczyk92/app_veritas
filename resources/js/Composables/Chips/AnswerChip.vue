<template>
    <v-chip variant="outlined" color="#0284c7" :size="options.size">
        odpowiedzi: {{ question?.choices ? question.choices.length : question.closed_choices.length }}
    </v-chip>
</template>

<script setup>

defineProps({ question: Object })

const options = {
    size: 'small'
}

</script>
