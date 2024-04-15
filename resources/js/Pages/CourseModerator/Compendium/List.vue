<template>

    <Head title="VeritasApp - moderator kursów - lista kompendiów" />
    <CourseModeratorLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <v-card variant="tonal" :color="'white'" class="tw-mb-4 tw-shadow-2xl !tw-px-4">
                <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                    <div class="tw-flex tw-items-center tw-mb-4 tw-flex-row tw-pt-4 tw-justify-between">
                        <div>
                            <v-btn variant="outlined" @click="router.visit(route('course_moderator.compendium.create'))"
                                class="tw-shadow-lg" size="large" color="#22c55e">
                                <template v-slot:prepend>
                                    <i class="fa-regular fa-file-circle-plus tw-text-lg"></i>
                                </template>
                                Dodaj kompendium
                            </v-btn>
                        </div>
                    </div>
                    <Transition mode="out-in">
                        <v-skeleton-loader color="#4b5563" type="table" v-if="loader"></v-skeleton-loader>
                        <div v-else>
                            <div class="tw-overflow-x-auto" v-if="compendiums.length > 0">
                                <v-data-table :items="compendiums" height="auto" item-value="id">
                                    <template #headers>
                                        <tr class="tw-bg-gray-200">
                                            <th>#ID</th>
                                            <th>Nazwa kompendium</th>
                                            <th>Widoczne dla</th>
                                            <th>Ilość lekcji</th>
                                            <th>Czas czytania</th>
                                            <th>Widoczność</th>
                                            <th>Uwtorzono</th>
                                            <!-- <th class="!tw-text-center">Przedź do użytkownika</th> -->
                                        </tr>
                                    </template>
                                    <template v-slot:item="{ item }">
                                        <tr class="tw-text-xs">
                                            <td>#{{ item.id }}</td>
                                            <td>
                                                <TableLink :url="`/moderator-kursow/kompendium/${item.id}`">
                                                    {{ item.name }}
                                                </TableLink>
                                            </td>
                                            <td>
                                                <div class="tw-flex tw-flex-row tw-gap-1"
                                                    v-if="item.company_branches.length > 0 || item.roles.length > 0">
                                                    <CompanyBranchChip v-for="(branch, index) in item.company_branches"
                                                        :company_branch="branch" />
                                                    <RoleChip v-for="(role, index) in item.roles" :role="role" />
                                                </div>
                                                <span v-else>brak</span>

                                            </td>
                                            <td>{{ item.lessons_count }}</td>
                                            <td>{{ time_to_min(item.time_to_read) }}</td>
                                            <td>
                                                <span
                                                    :class="item.visibility.id == 1 ? 'tw-text-orange-600' : 'tw-text-green-600'">
                                                    {{ item.visibility.name_pl }}
                                                </span>
                                            </td>
                                            <td>{{ format(item.created_at) }}</td>

                                        </tr>
                                    </template>
                                    <template #bottom></template>
                                </v-data-table>
                            </div>
                            <AlertInfo v-else title="" :show_icon="false">
                                Brak kompendiów do wyświetlenia
                            </AlertInfo>
                        </div>
                    </Transition>

                </v-card-text>
            </v-card>
        </MainContent>
    </CourseModeratorLayout>
</template>

<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { ref, watch, onMounted } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'
import TableLink from '@/Templates/HTML/TableLink.vue';

import { format } from '@/Components/Functions/DateFormat.vue';

import AlertInfo from '@/Components/Functions/AlertInfo.vue'

import RoleChip from '@/Composables/Chips/RoleChip.vue'
import CompanyBranchChip from '@/Composables/Chips/CompanyBranchChip.vue'

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Kompendia',
        disabled: false,
        href: route('course_moderator.compendium.index')
    }
]

const compendiums = ref([])
const pagination = ref({
    page: 1,
    per_page: 50,
    total: 0,
    total_pages: 0
})

const loader = ref(true)

const get = async () => {
    let response = await axios.get(route('course_moderator.compendium.list', {
        page: pagination.value.page,
        per_page: pagination.value.per_page
    }))

    response = response.data
    compendiums.value = response.compendiums.data

    loader.value = false
}

onMounted(async () => {
    await get()
})

console.table(compendiums.value)

const time_to_min = (time) => {
    return time ? time + 'min' : 'brak'
}



</script>
