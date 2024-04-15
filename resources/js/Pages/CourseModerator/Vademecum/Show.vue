<template>

    <Head title="VeritasApp - moderator kursów - kompendium" />
    <CourseModeratorLayout class="tw-bg-indigo-100">
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <Processing v-if="delete_compendium_progress" msg="Usuwanie vademecum wraz z zawartością..." />
        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1 tw-gap-4">

                <v-card :color="'white'" class="tw-mb-4 tw-shadow-2xl !tw-px-4" :title="vademecum.name"
                    :subtitle="'Utworzono: ' + format(vademecum.created_at)">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-memo-circle-info tw-text-2xl"></i>
                    </template>
                    <template v-slot:title>
                        <div class="tw-flex tw-flex-row tw-justify-between">
                            <div>{{ vademecum.name }}</div>
                            <v-btn color="#0284c7" text="Podgląd" variant="outlined" :disabled="false" size="small"
                                :href="route('course_moderator.vademecum.edit', {
                                    id: vademecum.id
                                })">
                                <template v-slot:prepend>
                                    <i class="fa-light fa-file-pen"></i>
                                </template>
                                Edytuj
                            </v-btn>
                        </div>
                    </template>
                    <template v-slot:subtitle>
                        <div class="tw-flex tw-flex-row tw-gap-2 tw-mt-2">
                            <v-chip variant="outlined">
                                Utworzono: {{ format(vademecum.created_at) }}
                            </v-chip>
                            <v-chip v-if="vademecum.lessons_count > 0" variant="outlined">
                                Ilość lekcji: {{ vademecum.lessons_count }}
                            </v-chip>
                            <v-chip variant="outlined" :color="vademecum.visibility.id == 1 ? '#ea580c' : '#16a34a'">
                                {{ vademecum.visibility.name_pl }}
                            </v-chip>
                        </div>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <div v-if="vademecum.roles.length > 0 || vademecum.company_branches.length > 0">
                            <h2 class="tw-text-lg tw-mb-2">Widoczność</h2>
                            <div v-if="vademecum.roles.length > 0" class="tw-flex tw-flex-row tw-gap-1">
                                <v-chip v-for="(role, index) in vademecum.roles" variant="outlined" color="#ea580c">
                                    {{ role.name_pl }}
                                </v-chip>
                            </div>
                            <div v-if="vademecum.company_branches.length > 0"
                                class="tw-flex tw-flex-row tw-gap-1 tw-mt-1">
                                <v-chip v-for="(branch, index) in vademecum.company_branches" variant="outlined"
                                    color="#16a34a">
                                    {{ branch.name }}
                                </v-chip>
                            </div>
                        </div>
                        <div v-html="vademecum.description" class="tw-mt-6"></div>
                    </v-card-text>
                </v-card>
                <v-card :color="'white'" class="tw-mb-4 tw-shadow-2xl !tw-px-4" title="Powiązane lekcje">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-network-wired tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <div>
                            <v-btn color="#22c55e" text="Podgląd" variant="outlined" :disabled="false"
                                :href="route('course_moderator.vademecum.lesson.create', { vademecum_id: vademecum.id })"><template
                                    v-slot:prepend>
                                    <i class="fa-sharp fa-solid fa-layer-plus"></i>
                                </template>
                                Dodaj nową lekcję
                            </v-btn>
                        </div>
                        <div v-if="lessons.length <= 0">
                            <AlertInfo>Brak lekcji do wyświetlenia</AlertInfo>
                        </div>
                        <div class="tw-relative" v-else>
                            <draggable v-model="lessons" group="people" @start="drag = true"
                                @end="drag = false; save_new_order()" item-key="id" class="tw-flex tw-flex-col tw-gap-2"
                                disabled>
                                <template #item="{ element }">
                                    <v-list-item :key="element.id"
                                        class="!tw-bg-gray-100 !tw-border !tw-border-gray-200 !tw-rounded-xl">
                                        <template v-slot:subtitle>
                                            <div class="tw-mt-1 tw-flex tw-flex-row tw-justify-between">
                                                <div>
                                                    Utworzono: {{ format(element.created_at) }}
                                                </div>
                                                <div class="tw-flex tw-flex-row tw-gap-2">
                                                    <div v-if="element.time_to_read"
                                                        class="tw-flex tw-flex-row tw-gap-2">
                                                        <i class="fa-regular fa-clock"></i>
                                                        <span>{{ element.time_to_read }} min</span>
                                                    </div>
                                                    <div v-if="element.time_to_read">|</div>
                                                    <div>
                                                        <!-- <i class="fa-solid fa-question tw-text-green-700"></i>
                                                        <i class="fa-solid fa-question tw-text-red-700"></i> -->
                                                        <i class="fa-sharp fa-solid fa-graduation-cap"></i>
                                                    </div>
                                                </div>

                                            </div>
                                        </template>
                                        <template v-slot:title>
                                            <a class="tw-text-blue-700 hover:tw-text-blue-900 hover:tw-underline" :href="route('course_moderator.vademecum.lesson.show', {
                                                vademecum_id: vademecum.id,
                                                lesson_id: element.id
                                            })">
                                                {{ element.name }}
                                            </a>
                                        </template>
                                        <template v-slot:prepend>
                                            <v-col cols="auto">
                                                <v-btn density="compact" icon="" color="#0284c7">{{ element.order
                                                    }}</v-btn>
                                            </v-col>
                                        </template>
                                    </v-list-item>
                                </template>
                            </draggable>
                            <Transition>
                                <div v-if="save_new_order_procession"
                                    class="tw-bg-gray-700 tw-p-4 tw-flex tw-flex-row tw-text-gray-100 tw-absolute tw-w-full tw-h-full tw-top-0 tw-items-center tw-justify-center tw-opacity-80 tw-rounded">
                                    <svg class="tw-animate-spin tw--ml-1 tw-mr-3 tw-h-5 tw-w-5 tw-text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4">
                                        </circle>
                                        <path class="tw-opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Zapisywanie nowej kolejności...
                                </div>
                            </Transition>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
            <div class="tw-flex tw-flex-row-reverse">
                <v-btn color="#dc2626" text="Podgląd" size="small" variant="tonal" :disabled="false"
                    @click="delete_compendium()">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-trash"></i>
                    </template>
                    Usuń vademecum wraz ze wszystkimi danymi
                </v-btn>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<script setup>
import {
    Head,
    router
} from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import Processing from '@/Composables/Processing.vue'

import {
    ref,
    watch,
    onMounted
} from 'vue'
import TableLink from '@/Templates/HTML/TableLink.vue';

import {
    format
} from '@/Components/Functions/DateFormat.vue';

import {
    AlertStore
} from '@/Pinia/AlertStore'

import draggable from 'vuedraggable'
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

const my_array = ref([
    {
        id: 1,
        name: 'Lekcja 1'
    },
    {
        id: 2,
        name: 'Lekcja 2'
    },
    {
        id: 3,
        name: 'Lekcja 3'
    },
    {
        id: 4,
        name: 'Lekcja 4'
    },
    {
        id: 5,
        name: 'Lekcja 5'
    }
])

const props = defineProps({
    vademecum: {
        type: Object,
        required: true
    }
})

const lessons = ref(props.vademecum.lessons)

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Vademeca',
        disabled: false,
        href: route('course_moderator.vademecum.index')
    },
    {
        title: props.vademecum.name,
        disabled: true
    }
]

const save_new_order_procession = ref(false)

const save_new_order = async () => {
    save_new_order_procession.value = true

    let response = await axios.patch(route('course_moderator.compendium.lesson.order.update', {
        id: props.vademecum.id
    }), {
        lessons: lessons.value
    })

    response = response.data

    if (response.success) {
        lessons.value = response.lessons
    }

    alert_store.pushAlert(response.alert_type, response.msg)
    save_new_order_procession.value = false
}


const alert_store = AlertStore()

const delete_compendium_progress = ref(false)

const delete_compendium = async () => {
    if (confirm('Na pewno chcesz usunąć kompendium wraz z całą zawartością?')) {
        delete_compendium_progress.value = true

        let response = await axios.delete(route('course_moderator.compendium.destroy', props.vademecum.id))
        response = response.data

        alert_store.pushAlert(response.alert_type, response.msg)

        if (response.success) {
            router.visit(route('course_moderator.vademecum.index'))
        } else {
            delete_compendium_progress.value = false
        }
    }
}
</script>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
