<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { ref, watch, onMounted } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

import AlertInfo from '@/Components/Functions/AlertInfo.vue'

import TInput from '@/Composables/Form/TInput.vue'
import Editor from '@tinymce/tinymce-vue'
import InputError from '@/Components/InputError.vue'


import { format } from '@/Components/Functions/DateFormat.vue'

import TableLink from '@/Templates/HTML/TableLink.vue'
// import Preview from './Preview.vue'

import { upload_file } from '@/Composables/UploadFilesTinyMCE'

import MCEEditor from '@/Composables/MCEEditor.vue'

import Processing from '@/Composables/Processing.vue'


import CompanyBranchChip from '@/Composables/Chips/CompanyBranchChip.vue'
import RoleChip from '@/Composables/Chips/RoleChip.vue'
import LessonChip from '@/Composables/Chips/LessonChip.vue'
import VademecumChip from '@/Composables/Chips/VademecumChip.vue'
import CompendiumChip from '@/Composables/Chips/CompendiumChip.vue'

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Testy',
        disabled: true,
    }
]

const loader = ref(true)
const tests = ref([])

const pagination = ref({
    page: 1,
    per_page: 50,
    total: 0,
    total_pages: 0
})

const get_list = async () => {
    let response = await axios.post(route('course_moderator.test.list', {
        page: pagination.value.page,
        per_pate: pagination.value.per_page
    }))

    response = response.data

    tests.value = response.tests.data
    console.log(tests.value)
    loader.value = false
}

onMounted(async () => {
    await get_list()
})

const has_lesson_or_compendium = (item) => {
    return (item.lesson.length > 0 || item.compendium.length > 0)
}

// const question_type_changed = async () => {
//     console.log('zmiana typu pytania')
//     await sleep(1)
//     current_question.value = null

//     console.log('current question', current_question_type.value)

// }

</script>


<template>

    <Head title="VeritasApp - moderator kursów - lista testów" />
    <CourseModeratorLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <v-card-text class="!tw-p-0">
                <Transition mode="out-in">
                    <v-skeleton-loader color="#4b5563" type="table" v-if="loader"></v-skeleton-loader>
                    <div v-else>
                        <div class="tw-overflow-x-auto" v-if="tests.length > 0">
                            <v-data-table :items="tests" height="auto" item-value="id" items-per-page="-1">
                                <template #headers>
                                    <tr class="tw-bg-gray-200">
                                        <th>#ID</th>
                                        <th>Nazwa</th>
                                        <th>Przypisane do</th>
                                        <th>Widoczność</th>
                                        <th>Dostępna dla</th>
                                        <th>Czas na rozwiązanie</th>
                                        <th>Ilość pytań</th>
                                        <th>Utworzono</th>
                                        <!-- <th class="!tw-text-center">Przedź do użytkownika</th> -->
                                    </tr>
                                </template>
                                <template v-slot:item="{ item }">
                                    <tr class="tw-text-xs">
                                        <td>{{ item.id }}</td>
                                        <td>
                                            <TableLink :url="route('course_moderator.test.show', {
                                                id: item.id
                                            })">
                                                {{ item.name }}
                                            </TableLink>
                                        </td>
                                        <td>
                                            <span v-if="has_lesson_or_compendium(item)">
                                                <div v-if="item.lesson.length > 0"
                                                    class="tw-flex tw-flex-col tw-gap-1 tw-py-1">
                                                    <div>
                                                        <LessonChip v-if="item.lesson.length > 0"
                                                            :lesson_name="item.lesson[0].name" />
                                                    </div>
                                                    <div>
                                                        <CompendiumChip variant="outlined" class="tw-opacity-40"
                                                            v-if="item.lesson[0].lessonable.compendium_type_id == 1"
                                                            :compendium_name="item.lesson[0].lessonable.name" />
                                                        <VademecumChip variant="outlined" class="tw-opacity-40"
                                                            v-if="item.lesson[0].lessonable.compendium_type_id == 2"
                                                            :vademecum_name="item.lesson[0].lessonable.name" />
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <CompendiumChip
                                                        v-if="item.compendium[0] && item.compendium[0].compendium_type_id == 1"
                                                        :compendium_name="item.compendium[0].name" />
                                                    <VademecumChip
                                                        v-if="item.compendium[0] && item.compendium[0].compendium_type_id == 2"
                                                        :vademecum_name="item.compendium[0].name" />
                                                </div>
                                            </span>
                                            <span v-else class="tw-text-orange-700">brak przypisania</span>
                                        </td>
                                        <td>
                                            <span
                                                :class="item.visibility_id == 1 ? 'tw-text-orange-600' : 'tw-text-green-600'">
                                                {{ item.visibility.name_pl }}
                                            </span>
                                        </td>
                                        <td class="tw-max-w-[400px]">
                                            <div class="tw-flex tw-gap-1 tw-flex-wrap tw-py-1">
                                                <RoleChip v-for="(role, index) in item.roles" :role="role" size="small"
                                                    :key="index" />
                                                <CompanyBranchChip v-for="(branch, index) in item.company_branches"
                                                    :company_branch="branch" size="small" :key="index" />
                                            </div>
                                        </td>
                                        <td>{{ item.time ? item.time + 'm' : '-' }}</td>
                                        <td>{{ item.questions_count }}</td>
                                        <td>{{ format(item.created_at) }}</td>
                                    </tr>
                                </template>
                                <template #bottom></template>
                            </v-data-table>
                        </div>
                        <AlertInfo v-else title="" :show_icon="false">
                            Brak użytkowników do wyświetlenia
                        </AlertInfo>
                    </div>
                </Transition>
            </v-card-text>
        </MainContent>
    </CourseModeratorLayout>
</template>

<style></style>