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
                <!-- <NewPagination :pagination="offers"></NewPagination> -->
                <div class="tw-bg-gray-100 tw-rounded tw-shadow-xl">
                    <v-data-table :headers="headers" :items="get_offers" items-per-page-text="Ilość ofert na stronę">
                        <template #item.caretaker_fullname="{ item }">
                            <a class="edit-user tw-text-blue-600 hover:tw-text-400 hover:tw-underline"
                                :href="`/uzytkownik/${item.user_id}`">
                                {{ item.caretaker_fullname }}
                            </a>
                        </template>
                        <template #item.url_to_family_profile="{ item }">
                            <a class="edit-user"
                                :href="`https://local.grupa-veritas.pl/#/rodziny/${item.crm_family_id}`"
                                target="_blank">
                                <i class="tw-text-xl fa-solid fa-globe"></i>
                            </a>
                        </template>
                        <template #item.actions="{ item }">
                            <div class="tw-flex tw-flex-row tw-gap-2">
                                <v-tooltip text="Usuń zgłoszenie" location="top">
                                    <template v-slot:activator="{ props }">
                                        <v-btn icon size="small" color="#dc2626" v-bind="props">
                                            <i class="fa-regular fa-trash tw-text-base"></i>
                                        </v-btn>
                                    </template>
                                </v-tooltip>

                            </div>
                        </template>
                    </v-data-table>
                    <!-- <TableDefault :headers="headers">
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

                    </TableDefault> -->
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

import { ref, computed } from 'vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

const props = defineProps({
    offers: {
        type: Object,
        required: true
    }
})

console.log('oferty', props.offers)

const headers = [
    {
        title: 'ID oferty',
        value: 'id'
    },
    {
        title: 'imię i nazwisko opiekunki',
        value: 'caretaker_fullname'
    },
    {
        title: 'rodzina w CRM',
        value: 'url_to_family_profile'
    },
    {
        title: 'kod HP',
        value: 'hp_code'
    },
    {
        title: 'data rozpoczęcia',
        value: 'arrival_date'
    },
    {
        title: 'data utworzenia',
        value: 'created_at'
    },
    {
        title: 'Akcje',
        value: 'actions'
    },
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

const get_offers = computed(() => {
    return props.offers.data.map((item) => {
        // console.log('offer', item)
        return {
            id: item.id,
            caretaker_fullname: item.user.user_profiles.full_name,
            user_id: item.user.id,
            crm_family_id: item.crm_family_id,
            created_at: format(item.created_at),
            arrival_date: item.start_date,
            hp_code: item.hp_code
        }
    })
})

</script>
