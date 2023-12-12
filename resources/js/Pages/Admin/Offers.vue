<template>
    <Head title="VeritasApp - zgłoszenia na oferty" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Zgłoszenia na oferty</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 px-4 lg:px-8" v-if="offers.data.length > 0">
                <NewPagination
                    :pagination="offers"
                ></NewPagination>
                <div class="bg-gray-100 shadow-xl rounded">
                    <TableDefault :headers="headers">
                        <tr class="hover:bg-grey-lighter" v-for="(offer, index) in offers.data">
                            <td class="py-4 px-6 border-b border-grey-light">#{{ offer.crm_offer_id }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ offer.user.user_profiles.first_name }} {{ offer.user.user_profiles.last_name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">
                                <a class="edit-user" :href="`/uzytkownik/${offer.user.id}`">
                                    <i class="fa-solid fa-user-pen text-xl"></i>
                                </a>
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">
                                <a class="edit-user" :href="`https://local.grupa-veritas.pl/#/rodziny/${offer.crm_family_id}`" target="_blank">
                                    <i class="fa-solid fa-globe text-xl"></i>
                                </a>
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ offer.hp_code }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ offer.start_date }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(offer.created_at) }}</td>
                        </tr>

                    </TableDefault>
                </div>
            </div>
            <div class="max-w-8xl mx-auto sm:px-6 px-4 lg:px-8" v-else>
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

</script>