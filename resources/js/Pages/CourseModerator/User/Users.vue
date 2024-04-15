<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import AlertInfo from '@/Components/Functions/AlertInfo.vue'

import { onMounted, ref } from 'vue'

import { format } from '@/Components/Functions/DateFormat.vue'
import TableLink from '@/Templates/HTML/TableLink.vue'

import CompanyBranchChip from '@/Composables/Chips/CompanyBranchChip.vue'
import RoleChip from '@/Composables/Chips/RoleChip.vue'

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: true,
    }
]


const users = ref([])
const loader = ref(true)

const pagination = ref({
    page: 1,
    per_page: 50,
    total: 0,
    total_pages: 0
})

onMounted(async () => {
    await get()
})

const last_login = (item) => {
    return item.last_login.length > 0 ? format(item.last_login[0].created_at) : 'Brak logowań'
}

const get = async () => {
    let response = await axios.get(route('course_moderator.users.recruiters.get', {
        page: pagination.value.page,
        per_page: pagination.value.per_page
    }))

    response = response.data
    users.value = response.users.data
    console.log(users.value)

    loader.value = false
}

const search = async () => {

}


</script>

<template>

    <Head title="VeritasApp - moderator kursów - użytkownicy" />
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
                <div class="tw-flex tw-items-center tw-mb-4 tw-flex-row tw-pt-4 tw-justify-between">
                    <div>
                        <v-btn variant="outlined" @click="router.visit(route('course_moderator.create_user'))"
                            class="tw-shadow-lg" size="large" color="#22c55e">
                            <template v-slot:prepend><i class="fa-solid fa-plus tw-mr-2"></i></template>
                            Dodaj użytkownika
                        </v-btn>
                    </div>
                    <div class="tw-w-full lg:tw-w-[50%] md:tw-w-[65%]">
                        <v-text-field label="Szukaj" class="tw-w-full" variant="solo-filled"
                            placeholder="wyszukaj użytkownika..." hide-details v-model="search_string"
                            @keydown.enter="search()"></v-text-field>
                    </div>
                </div>
                <div class="tw-flex tw-flex-row tw-justify-end tw-gap-2 tw-mb-4">
                    <v-btn variant="outlined" @click="search()" class="tw-shadow-lg" color="#22c55e">
                        <template v-slot:prepend><i class="fa-solid fa-magnifying-glass"></i></template>
                        Szukaj
                    </v-btn>
                </div>
                <v-card-text class="!tw-p-0">
                    <Transition mode="out-in">
                        <v-skeleton-loader color="#4b5563" type="table" v-if="loader"></v-skeleton-loader>
                        <div v-else>
                            <div class="tw-overflow-x-auto" v-if="users.length > 0">
                                <v-data-table :items="users" height="auto" item-value="id" items-per-page="-1">
                                    <template #headers>
                                        <tr class="tw-bg-gray-200">
                                            <th>#ID</th>
                                            <th>Nazwisko i imię</th>
                                            <th>Adres e-mail</th>
                                            <th>Role</th>
                                            <th>Oddziały</th>
                                            <th>Ukończonych kursów</th>
                                            <th>Ostatnie logowanie</th>
                                            <th>Utworzono</th>
                                            <!-- <th class="!tw-text-center">Przedź do użytkownika</th> -->
                                        </tr>
                                    </template>
                                    <template v-slot:item="{ item }">
                                        <tr class="tw-text-xs">
                                            <td>#{{ item.id }}</td>
                                            <td>
                                                <TableLink :url="`/moderator-kursow/uzytkownik/${item.id}`">
                                                    {{ item.user_profiles.last_name }} {{ item.user_profiles.first_name
                                                    }}
                                                </TableLink>
                                            </td>
                                            <td>
                                                {{ item.email }}
                                            </td>
                                            <td class="tw-max-w-[400px]">
                                                <!-- {{ item.roles }} -->
                                                <div v-if="item.roles.length > 0"
                                                    class="tw-flex tw-flex-row tw-gap-1 tw-flex-wrap tw-py-1">
                                                    <RoleChip v-for="(role, index) in item.roles" :role="role" />
                                                </div>
                                                <span v-else class="tw-text-red-700">brak ról</span>
                                            </td>
                                            <td class="tw-max-w-[400px]">
                                                <div v-if="item.company_branches.length > 0"
                                                    class="tw-flex tw-flex-row tw-gap-1 tw-flex-wrap tw-py-1">
                                                    <CompanyBranchChip v-for="(branch, index) in item.company_branches"
                                                        :company_branch="branch" />
                                                </div>
                                                <span v-else class="tw-text-red-700">brak oddziału</span>
                                            </td>
                                            <td>0 / 57</td>
                                            <td>
                                                <span v-if="item.last_login.length > 0" class="tw-text-green-700">
                                                    {{ format(item.last_login[0].created_at) }}
                                                </span>
                                                <span v-else class="tw-text-red-700">brak logowań</span>
                                                <!-- {{ last_login(item) }} -->
                                            </td>
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
            </v-card>
        </MainContent>
    </CourseModeratorLayout>
</template>
