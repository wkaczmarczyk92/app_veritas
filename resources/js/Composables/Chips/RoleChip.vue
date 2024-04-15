<template>
    <v-chip variant="tonal" color="#4338ca">
        {{ role.name_pl }}
    </v-chip>
</template>

<script setup>

defineProps({ role: Object })

</script>
