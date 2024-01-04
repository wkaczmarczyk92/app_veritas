<script setup>

import LevelInfo from '@/Components/Templates/LevelInfo.vue';
import Header from '@/Components/Templates/Header.vue';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true
    }
})

</script>

<template>
    <section
        class="tw-bg-gray-800 tw-overflow-hidden tw-shadow-xl tw-rounded-lg tw-px-6 sm:tw-px-20 tw-pt-16 tw-pb-8 sm:tw-pb-28 tw-mt-10 tw-relative">
        <Header :center="true" color="tw-text-gray-100" h="2" value="Sprawdź swoje postępy">
        </Header>
        <div class="tw-grid tw-gap-4 tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-mt-8">
            <LevelInfo :levels="levels" :user="user" :index="0"></LevelInfo>
            <LevelInfo :levels="levels" :user="user" :index="1"></LevelInfo>
            <LevelInfo :levels="levels" :user="user" :index="2"></LevelInfo>
            <LevelInfo :levels="levels" :user="user" :index="3"></LevelInfo>
        </div>
    </section>
</template>
