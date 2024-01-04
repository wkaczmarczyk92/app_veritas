<template>
    <v-card variant="tonal" :color="'white'" class="tw-shadow-2xl">
        <template #title>
            <div class="tw-flex tw-flex-row tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-text-lg tw-font-bold tw-text-gray-800">
                    Ostatnie zgłoszenia na oferty
                </div>
                <a :href="route('offer.index')"
                    class="tw-text-sm tw-text-blue-500 hover:tw-underline hover:tw-text-blue-700">
                    Zobacz wszystkie
                    <span>
                        <i class="fa-sharp fa-regular fa-file-magnifying-glass"></i>
                    </span>
                </a>
            </div>

        </template>
        <v-card-text>
            <div class="tw-overflow-x-auto" v-if="offers.length > 0">
                <v-data-table :items="offers" height="auto" item-value="id">
                    <template #headers>
                        <tr class="tw-bg-gray-200">
                            <th v-for="(header, index) in headers" :key="index">
                                <span class="tw-font-bold">{{ header }}</span>
                            </th>
                        </tr>
                    </template>
                    <template v-slot:item="{ item }">
                        <tr class="tw-text-xs">
                            <td>#{{ item.crm_offer_id }}</td>
                            <td>{{ item.user.user_profiles.first_name }} {{
                                item.user.user_profiles.last_name }}</td>
                            <td class="tw-text-lg tw-text-center">
                                <a class="tw-edit-user" :href="`/uzytkownik/${item.user.id}`">
                                    <i class="tw-text-blue-500 fa-solid fa-user-pen hover:tw-text-blue-700"></i>
                                </a>
                            </td>
                            <td class="tw-text-lg tw-text-center">
                                <a class="tw-edit-user"
                                    :href="`https://local.grupa-veritas.pl/#/rodziny/${item.crm_family_id}`"
                                    target="_blank">
                                    <i class="fa-solid fa-globe tw-text-amber-500 hover:tw-text-amber-700"></i>
                                </a>
                            </td>
                            <td>{{ item.hp_code }}</td>
                        </tr>
                    </template>
                    <template #bottom></template>
                </v-data-table>
            </div>
            <AlertInfo v-else title="" :show_icon="false">
                Brak ofert do wyświetlenia.
            </AlertInfo>
        </v-card-text>
    </v-card>
</template>

<script setup>

import TableDefault from '@/Components/Templates/TableDefault.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import Header from '@/Templates/HTML/Header.vue';

defineProps({
    offers: {
        type: Object,
        required: true
    }
})

const headers = [
    'ID oferty',
    'opiekunka',
    'link do profilu',
    'rodzina w CRM',
    'kod HP'
]

</script>
