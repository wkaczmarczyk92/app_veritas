<template>
    <a :href="url">
        <v-card :title="item.name" :subtitle="subtitle"
            class="hover:tw-cursor-pointer hover:tw-bg-gray-200 !tw-transition !tw-duration-400 !tw-ease-in-out !tw-border"
            :class="visibility != null ? (visibility == 2 ? '!tw-border-green-300' : '!tw-border-orange-300') : ''">
            <template v-slot:title>
                <span class="tw-text-base">{{ item.name }}</span>
            </template>
            <v-card-text class="tw-pt-0">
                {{ item.description.slice(0, 100) }}...
            </v-card-text>
        </v-card>
    </a>
</template>

<script setup>

defineProps({
    item: Object,
    url: String,
    subtitle: {
        type: String,
        default: ''
    },
    visibility: {
        type: Number,
        default: null
    }
})

</script>


<style>
.v-card-item {
    padding-bottom: 0 !important;
}
</style>
