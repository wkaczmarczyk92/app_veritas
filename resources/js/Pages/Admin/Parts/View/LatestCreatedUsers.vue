<script setup>

import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import Header from '@/Templates/HTML/Header.vue';
import Table from '@/Templates/HTML/Table.vue';

import TableLink from '@/Templates/HTML/TableLink.vue';

defineProps({
    users: {
        type: Object,
        required: true
    }
})

const headers = [
    'PESEL',
    'Imię i nazwisko',
    'Rekruter',
]

</script>
<template>
    <v-card variant="tonal" :color="'white'" class="tw-shadow-2xl">
        <template #title>
            <div class="tw-flex tw-flex-row tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-text-lg tw-font-bold tw-text-gray-800">
                    Ostatnio dodani użytkownicy
                </div>
                <a :href="route('users')" class="tw-text-sm tw-text-blue-500 hover:tw-underline hover:tw-text-blue-700">
                    Zobacz wszystkich
                    <span>
                        <i class="fa-solid fa-user-magnifying-glass"></i>
                    </span>
                </a>
            </div>

        </template>
        <v-card-text>
            <div class="tw-overflow-x-auto" v-if="users.length > 0">
                <v-data-table :items="users" height="auto" item-value="id">
                    <template #headers>
                        <tr class="tw-bg-gray-200">
                            <th v-for="(header, index) in headers" :key="index">
                                <span class="tw-font-bold">{{ header }}</span>
                            </th>
                        </tr>
                    </template>
                    <template v-slot:item="{ item }">
                        <tr class="tw-text-xs">
                            <td>{{ item.pesel }}</td>
                            <td>
                                <TableLink :url="`/uzytkownik/${item.id}`">
                                    {{ item.user_profiles.first_name }} {{ item.user_profiles.last_name }}
                                </TableLink>
                            </td>
                            <td>
                                {{ `${item.user_profiles.recruiter_first_name ??
                                    '-'}${item.user_profiles.recruiter_last_name ??
                                    ''}` }}
                            </td>
                        </tr>
                    </template>
                    <template #bottom></template>
                </v-data-table>
            </div>
            <AlertInfo v-else title="" :show_icon="false">
                Brak nowych użytkowników.
            </AlertInfo>
        </v-card-text>
    </v-card>
</template>
