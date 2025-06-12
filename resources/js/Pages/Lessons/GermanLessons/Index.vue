<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AlertStore } from '@/Pinia/AlertStore';
import { ref } from 'vue'
import Lessons from '@/Pages/Lessons/GermanLessons/Templates/Lessons.vue'

const props = defineProps({
    german_lesson: {
        type: [Object, null],
        requried: true
    }
})

const german_lesson = ref(props.german_lesson)
const alert_store = AlertStore()

console.log(props.german_lesson)

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: true
    },
    {
        title: 'Lekcje niemieckiego',
        disabled: false,
        href: route('german.lessons.index')
    }
]

const destroy = async (id) => {
    if (confirm('Na pewno chcesz usunąć wybraną lekcję?')) {
        try {
            let response = await axios.delete(route('german.lessons.destroy', {
                id: id
            }))

            response = response.data
            console.log('destroy response', response)

            alert_store.pushAlert(response)

            if (response.success) {
                german_lesson.value.lessons = german_lesson.value.lessons.filter(item => {
                    return item.id != id
                })
                // router.get(route('german.lessons.index'))
            }
        } catch (error) {
            console.log(error)
            alert_store.exception()
        }
    }
}

</script>

<template>

    <Head title="VeritasApp - Lekcje język niemieckiego" />

    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <div class="tw-flex tw-flex-col tw-gap-2">
                    <div>
                        <v-btn color="#9333ea" :href="route('german.lessons.create')">Dodaj lekcję</v-btn>
                    </div>
                    <Lessons :german_lesson="german_lesson" :admin_view="true" @destroy="destroy($event)" />
                    <!-- <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-4 tw-gap-4"
                        v-if="german_lesson.lessons.length > 0">
                        <v-card v-for="(lesson, index) in german_lesson.lessons" :key="lesson.id"
                            :title="(index + 1) + '. ' + lesson.name">
                            <template v-slot:title>
                                <div class="tw-flex tw-flex-row tw-justify-between">
                                    <div class="tw-break-all">
                                        {{ index + 1 }}. {{ lesson.name }}
                                    </div>
                                    <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">

                                        <i class="fas fa-circle"
                                            :class="lesson.visibility.id == 1 ? 'tw-text-[#ea580c]' : 'tw-text-[#16a34a]'">
                                            <v-tooltip activator="parent" location="top">{{ lesson.visibility.name_pl
                                            }}</v-tooltip>
                                        </i>
                                        <v-menu>
                                            <template v-slot:activator="{ props }">
                                                <v-btn v-bind="props" icon variant="text">
                                                    <i
                                                        class="fas fa-ellipsis-v tw-text-2xl hover:tw-cursor-pointer hover:tw-text-blue-600"></i>
                                                </v-btn>
                                            </template>

                                            <v-list>
                                                <v-list-item :href="route('german.lessons.show', { id: lesson.id })">
                                                    <v-list-item-title>Szczegóły</v-list-item-title>
                                                </v-list-item>
                                                <v-list-item @click="destroy(lesson.id)">
                                                    <v-list-item-title>Usuń</v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </div>
                                </div>
                            </template>
                            <v-card-text>

                            </v-card-text>
                            <v-card-actions>
                                <div class="tw-flex tw-flex-row tw-justify-end tw-w-full">
                                    <div class="tw-text-sm tw-italic">
                                        Dodane przez: {{ lesson.user.email }}
                                    </div>
                                </div>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <v-alert v-else color="info">Brak lekcji do wyświetlenia.</v-alert> -->
                </div>
            </div>
        </div>
    </AdminLayout>

</template>