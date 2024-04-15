<template>
    <v-chip variant="tonal" color="#4d7c0f">
        {{ company_branch.name }}
    </v-chip>
</template>

<script setup>

defineProps({ company_branch: Object })

</script>
