<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import CreateForm from './CreateForm.vue';

import { onMounted, ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import EditForm from './EditForm.vue';

import Processing from '@/Composables/Processing.vue';

const processing_msg = ref('Usuwanie filmu...')

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Webinaria',
        disabled: true,
    }
]

const videos = ref([])
const alert_store = AlertStore()

const pagination = ref({
    page: 1,
    per_page: 50,
    total: 0,
    total_pages: 0
})

onMounted(async () => {
    await get_list()
})

const get_list = async () => {
    let response = await axios.get(route('course_moderator.video.list', {
        page: pagination.value.page,
        per_page: pagination.value.per_page
    }))

    response = response.data
    videos.value = response.videos.data
}

const delete_processing = ref(false)

const delete_video = async (id, index) => {
    if (confirm('Na pewno chcesz usunąć film?')) {
        delete_processing.value = true
        processing_msg.value = 'Usuwanie filmu...'
        let response = await axios.delete(route('course_moderator.video.destroy', { video: videos.value[index] }))

        response = response.data

        if (response.success) {
            videos.value.splice(index, 1)
        }

        alert_store.pushAlert(response.alert_type, response.msg)
        delete_processing.value = false
    }
}

const edit_video = ref(null)

const close_edit = async (updated_video = false) => {
    edit_video.value = null

    let searched_index = null

    if (updated_video) {
        delete_processing.value = true
        processing_msg.value = 'Aktualizacja filmów...'
        await get_list()
    }

    delete_processing.value = false
}

</script>

<template>

    <Head title="VeritasApp - moderator kursów - webinaria" />
    <CourseModeratorLayout>
        <EditForm v-if="edit_video != null" :video="edit_video" @close="close_edit($event)" />
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <Processing v-if="delete_processing" :msg="processing_msg" />
        <MainContent class="tw-relative">
            <div class="tw-flex tw-flex-row tw-mb-4">
                <CreateForm @new-video="videos.push($event)" />
            </div>
            <TransitionGroup tag="div" class="tw-grid lg:tw-grid-cols-4 tw-grid-cols-1 tw-gap-4 tw-relative"
                v-if="videos.length > 0">
                <div class="tw-shadow-xl tw-shadow-gray-600 tw-rounded-xl" v-for="(video, index) in videos">
                    <div class="tw-bg-gray-300 tw-relative tw-w-full tw-pt-[56.25%] tw-rounded-xl">
                        <div class="tw-absolute tw-top-2 tw-right-2 tw-z-10 tw-flex tw-flex-row tw-gap-1">
                            <i @click="delete_video(video.id, index)"
                                class="fa-regular fa-trash tw-text-2xl tw-text-red-600 hover:tw-cursor-pointer hover:tw-text-red-400"></i>
                            <i @click="edit_video = video"
                                class="fa-solid fa-pen-to-square tw-text-2xl tw-text-sky-600 hover:tw-cursor-pointer hover:tw-text-sky-400"></i>
                        </div>
                        <iframe class="tw-absolute tw-top-0 tw-left-0 tw-w-full tw-h-full tw-rounded-t-xl"
                            :src="`https://player.vimeo.com/video/${video.vimeo_video_id}?h=a7f1c135d0&color=3d59a8&title=0&byline=0&portrait=0`"
                            width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                    <div class="tw-p-2" v-if="video.name">{{ video.name }}</div>
                </div>
            </TransitionGroup>
            <AlertInfo v-else>Brak filmów do wyświetlenia.</AlertInfo>


        </MainContent>
    </CourseModeratorLayout>
</template>

<style>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>


<!-- <iframe src="https://player.vimeo.com/video/651169060?h=84c1f8fdf9&color=3d59a8&title=0&byline=0&portrait=0" width="640"
    height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
<p><a href="https://vimeo.com/651169060">MODUŁ 1 - wyjście</a> from <a href="https://vimeo.com/user157664491">Akademia SenioPort</a> on <a href="https://vimeo.com">Vimeo</a>.</p>

<iframe src="https://player.vimeo.com/video/651169210?h=a7f1c135d0&color=3d59a8&title=0&byline=0&portrait=0" width="640"
    height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
<p><a href="https://vimeo.com/651169210">MODUŁ 2 - wyjście</a> from <a href="https://vimeo.com/user157664491">Akademia SenioPort</a> on <a href="https://vimeo.com">Vimeo</a>.</p>

https://vimeo.com/654094272/2fb0bf0e10 -->
