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
const payout_request_store = payoutRequestsStore();

payout_requests.value = await payout_request_store.load('completed');
payout_request_store.payout_requests_completed = payout_requests.value[0]


const headers = [
    {
        title: '#ID',
        value: 'id'
    },
    {
        title: 'Imię i nazwisko opiekunki',
        value: 'username'
    },
    {
        title: 'PESEL',
        value: 'user_has_bonus.user.pesel'
    },
    {
        title: 'Kwota bonusu',
        value: 'user_has_bonus.bonus_value'
    },
    {
        title: 'Za poziom',
        value: 'user_has_bonus.level_id'
    },
    {
        title: 'Zrealizowany przez',
        value: 'admin'
    },
    {
        title: 'Ostatnia aktualizacja (data)',
        value: 'updated_at',
        sortable: true
    },
];

</script>

<template>
    <div v-if="payout_request_store.payout_requests_completed.length <= 0">
        <StaticInfoAlert class="tw-mt-10">Brak zrealizowanych wniosków o wypłatę.</StaticInfoAlert>
    </div>
    <div v-else>
        <v-data-table
            v-if="payout_request_store.payout_requests_completed && payout_request_store.payout_requests_completed.length"
            :headers="headers" :items="payout_request_store.payout_requests_completed" item-value="id"
            items-per-page="50">
            <template #item.username="{ item }">
                <a :href="route('user', item.user_has_bonus.user.id)"
                    class="tw-text-blue-600 hover:tw-underline hover:tw-text-blue-900">{{
                        item.user_has_bonus.user.user_profiles.full_name }}</a>
            </template>
            <!-- <template #item.level="{ item }">
                {{ item }}
            </template> -->
            <template #item.user_has_bonus.bonus_value="{ value }">
                {{ value }} €
            </template>
            <template #item.updated_at="{ value }">
                {{ format(value) }}
            </template>
            <template #item.admin="{ item }">
                <div v-if="item.admin_user" class="tw-flex tw-flex-col tw-gap-1">
                    <div>{{ item.admin_user.user_profiles.first_name }} {{ item.admin_user.user_profiles.last_name }}</div>
                    <a class="tw-text-blue-600 hover:tw-underline" target="_blank" :href="`mailto:${item.admin_user.email}`">{{ item.admin_user.email }}</a>
                </div>
                <div v-else>brak</div>
            </template>
            <template #item.user_has_bonus.level_id="{ value }">
                <span :class="levelColor(value)"><b>{{ level(levels, value).toUpperCase() }}</b></span>
            </template>
        </v-data-table>
        <!-- <PaginationNoReload class="tw-mt-10" :pagination="payout_requests" @reload-request-by-url="reloadRequestsByURL"
            @reload-request-by-page-number="reloadRequestsByPageNumber"></PaginationNoReload>
        <div class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-mb-6"
            :class="payout_requests.total > payout_requests.per_page ? '' : 'tw-mt-10'">
            <div class="tw-px-6 tw-pt-6 tw-text-center">
                <Header h="2" value="Zrealizowane wnioski o wypłatę" :center="true"
                    icon="fa-sharp fa-solid fa-thumbs-up tw-mr-2" icon_color="tw-text-blue-600"></Header>
            </div>
            <TableDefault :headers="headers">
                <tr class="hover:bg-grey-lighter" v-for="(payout_request, index) in payout_requests.data">
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ payout_request.id }}</td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{
                        `${payout_request.user_has_bonus.user.user_profiles.first_name}
                        ${payout_request.user_has_bonus.user.user_profiles.last_name}` }}</td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{
                        payout_request.user_has_bonus.user.pesel }}</td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">
                        <a class="edit-user" :href="`/uzytkownik/${payout_request.user_has_bonus.user.id}`">
                            <i class="fa-solid fa-user-pen"></i>
                        </a>
                    </td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ payout_request.payout_value }} €
                    </td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">
                        <span :class="levelColor(payout_request.user_has_bonus.level_id)"><b>{{ level(levels,
                            payout_request.user_has_bonus.level_id).toUpperCase() }}</b></span>
                    </td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{
                        admin_name(payout_request.admin_user) }}</td>
                    <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ format(payout_request.updated_at) }}
                    </td>
                </tr>
            </TableDefault>
        </div>
        <PaginationNoReload class="tw-mt-6" :pagination="payout_requests" @reload-request-by-url="reloadRequestsByURL"
            @reload-request-by-page-number="reloadRequestsByPageNumber"></PaginationNoReload> -->
    </div>
</template>
