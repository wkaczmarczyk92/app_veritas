<script setup>

import { ref } from 'vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';
import TableDefault from '@/Components/Templates/TableDefault.vue';
import Header from '@/Components/Templates/Header.vue';
import { level, levelColor } from '@/Components/Functions/Level.vue';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
})

const payout_requests = ref({});
const usePayoutRequestStore = payoutRequestsStore();
payout_requests.value = await usePayoutRequestStore.loadRequestsData({page_name: 'complete_page', route_name: 'load.complete.payout.requests'});

async function reloadRequestsByURL(url) {
    payout_requests.value = await usePayoutRequestStore.loadRequestsData({page_name: 'complete_page', full_route: url});
}

async function reloadRequestsByPageNumber(page) {
    payout_requests.value = await usePayoutRequestStore.loadRequestsData({page: page, page_name: 'complete_page', route_name: 'load.complete.payout.requests'});
}

const headers = [
    '#ID',
    'Imię i nazwisko opiekunki',
    'Przejdź do użytkownika',
    'Kwota bonusu',
    'Za poziom',
    'Zrealizowani przez',
    'Zrealizowano (data)'
];

</script>

<template>
    <div v-if="payout_requests.data.length <= 0">
        <StaticInfoAlert class="mt-10">Brak zrealizowanych wniosków o wypłatę.</StaticInfoAlert>
    </div>
    <div v-else>
        <PaginationNoReload
            class="mt-10"
            :pagination="payout_requests"
            @reload-request-by-url="reloadRequestsByURL"
            @reload-request-by-page-number="reloadRequestsByPageNumber"
        ></PaginationNoReload>
        <div class="bg-gray-100 shadow-xl rounded mb-6" :class="payout_requests.total > payout_requests.per_page ? '' : 'mt-10'">
            <div class="px-6 pt-6 text-center">
                <Header
                    h="2"
                    value="Zrealizowane wnioski o wypłatę"
                    :center="true"
                    icon="fa-sharp fa-solid fa-thumbs-up mr-2"
                    icon_color="text-blue-600"
                ></Header>
            </div>
            <TableDefault :headers="headers">
                <tr class="hover:bg-grey-lighter" v-for="(payout_request, index) in payout_requests.data">
                    <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.id }}</td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ `${payout_request.user_has_bonus.user.user_profiles.first_name} ${payout_request.user_has_bonus.user.user_profiles.last_name}` }}</td>
                    <td class="py-4 px-6 border-b border-grey-light">
                        <a class="edit-user" :href="`/uzytkownik/${payout_request.user_has_bonus.user.id}`">
                            <i class="fa-solid fa-user-pen"></i>
                        </a>
                    </td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.payout_value }} €</td>
                    <td class="py-4 px-6 border-b border-grey-light">
                        <span
                        :class="levelColor(payout_request.user_has_bonus.level_id)"><b>{{ level(levels,
                            payout_request.user_has_bonus.level_id).toUpperCase() }}</b></span>
                    </td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ `${payout_request.admin_user.user_profiles.first_name} ${payout_request.admin_user.user_profiles.last_name}` }}</td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ format(payout_request.updated_at) }}</td>
                </tr>
            </TableDefault>
        </div>
        <PaginationNoReload
            class="mt-6"
            :pagination="payout_requests"
            @reload-request-by-url="reloadRequestsByURL"
            @reload-request-by-page-number="reloadRequestsByPageNumber"
        ></PaginationNoReload>
    </div>
    


</template>