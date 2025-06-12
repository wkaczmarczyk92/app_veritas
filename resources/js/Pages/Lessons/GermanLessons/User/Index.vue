<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import Lessons from '@/Pages/Lessons/GermanLessons/Templates/Lessons.vue'

const props = defineProps({
    german_lesson: {
        type: [Object, null],
        required: true
    }
})

console.log('german lessons', props.german_lesson)


</script>
<template>

    <Head title="VeritasApp - moje dane" />
    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-8xl md:tw-max-w-3xl sm:tw-px-6 lg:tw-px-8 ">
                <Lessons :german_lesson="german_lesson" :admin_view="false" />
            </div>
        </div>
    </UserLayout>
</template>