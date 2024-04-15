<template>
    <v-chip variant="outlined" class="" color="#9333ea" size="small">
        dopasowań: {{ question.matches.length
        }}
    </v-chip>
</template>

<script setup>

defineProps({ question: Object })

</script>