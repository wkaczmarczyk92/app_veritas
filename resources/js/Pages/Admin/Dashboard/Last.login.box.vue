<template>
    <v-card variant="tonal" :color="'white'" class="tw-shadow-2xl">
        <v-card-title class="tw-text-gray-800">
            <span class="tw-text-lg tw-font-bold">Ostatnie logowania:</span>
        </v-card-title>
        <v-card-text>
            <div class="tw-flex tw-flex-row tw-text-sm hover:tw-bg-gray-300" v-for="(item, date) in last_logins" :key="date"
                :class="item.max ? 'tw-font-bold tw-text-blue-700' : 'tw-text-gray-800'">
                <div class="tw-grow">
                    {{ date }}
                </div>
                <div class="tw-text-right tw-grow">
                    {{ item.count }}
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script setup>

defineProps({
    last_logins: {
        type: [Array, Object],
        default: []
    }
})


</script>
