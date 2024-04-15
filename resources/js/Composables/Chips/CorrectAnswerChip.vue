<template>
    <v-chip variant="outlined" class="" color="#16a34a" :size="options.size">
        poprawnych: {{
            question?.choices ? question.choices.filter((c: any) => c.is_correct == true).length :
                question.closed_choices.filter((c: any) => c.is_correct == true).length
        }}
    </v-chip>
</template>

<script setup lang="ts">

defineProps({ question: Object })

const options = {
    size: 'small'
}

</script>