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
    <section class="bg-gray-800 overflow-hidden shadow-xl rounded-lg px-6 sm:px-20 pt-16 pb-8 sm:pb-28 mt-10 relative">
        <Header
            :center="true"
            color="text-gray-100"
            h="2"
            value="Sprawdź swoje postępy">
        </Header>
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mt-8">
            <LevelInfo
                :levels="levels"
                :user="user"
                :index="0"
            ></LevelInfo>
            <LevelInfo
                :levels="levels"
                :user="user"
                :index="1"
            ></LevelInfo>
            <LevelInfo
                :levels="levels"
                :user="user"
                :index="2"
            ></LevelInfo>
            <LevelInfo
                :levels="levels"
                :user="user"
                :index="3"
            ></LevelInfo>
        </div>    
    </section>
</template>