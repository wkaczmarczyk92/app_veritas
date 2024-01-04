<template>
    <Head title="VeritasApp - zgłoszenia na oferty" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <div class="tw-py-12">
            <div class="tw-px-4 tw-mx-auto tw-max-w-8xl sm:tw-px-6 lg:tw-px-8" v-if="offers.data.length > 0">
                <NewPagination :pagination="offers"></NewPagination>
                <div class="tw-bg-gray-100 tw-rounded tw-shadow-xl">
                    <TableDefault :headers="headers">
                        <tr class="hover:tw-bg-grey-lighter" v-for="(offer, index) in offers.data">
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">#{{ offer.crm_offer_id }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                offer.user.user_profiles.first_name }} {{
        offer.user.user_profiles.last_name }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                <a class="edit-user" :href="`/uzytkownik/${offer.user.id}`">
                                    <i class="tw-text-xl fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                <a class="edit-user"
                                    :href="`https://local.grupa-veritas.pl/#/rodziny/${offer.crm_family_id}`"
                                    target="_blank">
                                    <i class="tw-text-xl fa-solid fa-globe"></i>
                                </a>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ offer.hp_code }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ offer.start_date }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ format(offer.created_at) }}</td>
                        </tr>

                    </TableDefault>
                </div>
            </div>
            <div class="tw-px-4 tw-mx-auto tw-max-w-8xl sm:tw-px-6 lg:tw-px-8" v-else>
                <StaticInfoAlert>Brak ofert do wyświetlenia.</StaticInfoAlert>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import TableDefault from '@/Components/Templates/TableDefault.vue';
import Pagination from '@/Components/Navigation/Pagination.vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';

import { ref } from 'vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

defineProps({
    offers: {
        type: Object,
        required: true
    }
})

const headers = [
    'ID oferty',
    'imię i nazwisko opiekunki',
    'link do profilu',
    'rodzina w CRM',
    'kod HP',
    'data rozpoczęcia',
    'data utworzenia',
]

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Zgłoszenia na oferty',
        disabled: true
    }
]

</script>
