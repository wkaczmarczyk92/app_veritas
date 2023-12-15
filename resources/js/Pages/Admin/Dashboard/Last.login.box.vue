<template>
    <h2 class="font-semibold text-md leading-tight mb-2">
        Ostatnie logowania:
    </h2>
    <div class="flex flex-row text-sm hover:bg-gray-300 px-2" v-for="(item, date) in last_logins" :key="date" :class="item.max ? 'font-bold text-blue-600' : ''">
        <div class="grow">
            {{date}}
        </div>
        <div class="grow text-right">
            {{ item.count }}
        </div>
    </div>
</template>

<script setup>

defineProps({
    last_logins: {
        type: [Array, Object],
        default: []
    }
})


</script>