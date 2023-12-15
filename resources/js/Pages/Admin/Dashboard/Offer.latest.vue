<template>
    <Header
        title="Ostatnie zgłoszenia na oferty"
        route_name="offer.index"
        route_title="Zobacz wszystkie"
        icon="fa-sharp fa-regular fa-file-magnifying-glass"
    ></Header>
    
    <TableDefault :headers="headers" class="text-xs" font_size="text-xs" v-if="offers.length > 0">
        <tr class="hover:bg-grey-lighter" v-for="(offer, index) in offers">
            <td class="py-2 px-4 border-b border-grey-light">#{{ offer.crm_offer_id }}</td>
            <td class="py-2 px-4 border-b border-grey-light">{{ offer.user.user_profiles.first_name }} {{ offer.user.user_profiles.last_name }}</td>
            <td class="py-2 px-4 border-b border-grey-light">
                <a class="edit-user" :href="`/uzytkownik/${offer.user.id}`">
                    <i class="fa-solid fa-user-pen text-xl"></i>
                </a>
            </td>
            <td class="py-2 px-4 border-b border-grey-light">
                <a class="edit-user" :href="`https://local.grupa-veritas.pl/#/rodziny/${offer.crm_family_id}`" target="_blank">
                    <i class="fa-solid fa-globe text-xl"></i>
                </a>
            </td>
            <td class="py-2 px-4 border-b border-grey-light">{{ offer.hp_code }}</td>
        </tr>
    </TableDefault>
    <AlertInfo
        v-else
        title=""
        :show_icon="false">
        Brak ofert do wyświetlenia.
    </AlertInfo>
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