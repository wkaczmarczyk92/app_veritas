<script setup>

import { format } from '@/Components/Functions/DateFormat.vue';
import { ref, watch } from 'vue';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';
import axios from 'axios';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import { AlertStore } from '@/Pinia/AlertStore';
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

payout_requests.value = await usePayoutRequestStore.loadRequestsData({page_name: 'incomplete_page'});

console.log(payout_requests.value)

async function reloadRequestsByURL(url) {
    payout_requests.value = await usePayoutRequestStore.loadRequestsData({page_name: 'incomplete_page', full_route: url});
    reset_all();
}

async function reloadRequestsByPageNumber(page) {
    payout_requests.value = await usePayoutRequestStore.loadRequestsData({page: page, page_name: 'incomplete_page'});
    reset_all();
}

const payout_reuqests_for_action = ref([]);
const spinner_visible = ref(false);
const select_all = ref(false);
const useAlertStore = AlertStore();

watch([select_all, payout_reuqests_for_action], ([new_select, new_action], [old_select, old_action]) => {
    if (new_select != old_select) {
        toggle_all(select_all.value);
    }

    if (new_action.length <= 0) {
        select_all.value = false;
    }
})

const toggle_all = (select) => {
    if (!select) {
        payout_reuqests_for_action.value = [];
    } else {
        payout_requests.value.data.forEach(item => {
            if (!payout_reuqests_for_action.value.includes(item.id)) {
                payout_reuqests_for_action.value.push(item.id);
            }
        })
    }
}

async function set_as_complete() {
    spinner_visible.value = true;
    disabled.value = true;
    await axios.patch(route('payoutrequests.update'), { to_update: payout_reuqests_for_action.value })
        .then(response => {
            if (response.data.success) {
                useAlertStore.pushAlert('success', 'Wybrane wnioski o wypłatę zostały oznaczone jako zrealizowane.');
            } else {
                useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.');
            }

            reloadRequestsByPageNumber(1);
            usePayoutRequestStore.countIncomplete();
            select_all.value = false;
            spinner_visible.value = false;
            payout_reuqests_for_action.value = [];
            disabled.value = false;
        });
}

const reset_all = () => {
    payout_reuqests_for_action.value = [];
    select_all.value = false;
}

const disabled = ref(false);

const headers = [
    '',
    '#ID',
    'Imię i nazwisko opiekunki',
    'Przejdź do użytkownika',
    'Kwota bonusu',
    'Za poziom',
    'Data utworzenia'
];

</script>

<template>
    <div v-if="payout_requests && payout_requests.data.length <= 0">
        <StaticInfoAlert class="mt-10">Brak nowych wniosków o wypłatę.</StaticInfoAlert>
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
                    value="Oczekujące wnioski do wypłaty"
                    :center="true"
                    icon="fa-solid fa-hourglass-clock mr-2"
                    icon_color="text-orange-500"
                ></Header>
            </div>
            <div class="flex flex-row mt-6 text-gray-800 mb-3 px-6 pt-2">
                <div class="flex">
                    <i class="fa-regular fa-arrow-turn-down fa-flip-horizontal mr-2 mt-auto mb-auto"></i> 
                    <div class="flex items-center mr-3">
                        <input type="checkbox" id="for_all" v-model="select_all" class="h-4 w-4 rounded border-gray-400 text-gray-800 focus:ring-0">
                    </div>
                    <label for="for_all">Z zaznaczonymi:</label>
                </div>
                <div class="ml-4 underline text-green-600 hover:text-green-800 hover:cursor-pointer" @click="set_as_complete" :class="disabled ? 'pointer-events-none' : ''">
                    oznacz jako Zrealizowane
                </div>
            </div>
            <TableDefault :headers="headers">
                <tr class="hover:bg-grey-lighter" v-for="(payout_request, index) in payout_requests.data">
                    <td class="py-4 px-6 border-b border-grey-light">
                        <div class="flex items-center">
                            <input type="checkbox" v-model="payout_reuqests_for_action" :value="payout_request.id" class="h-4 w-4 rounded border-gray-400 text-gray-800 focus:ring-0">
                        </div>
                    </td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.id }}</td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ `${payout_request.user_has_bonus.user.user_profiles.first_name} ${payout_request.user_has_bonus.user.user_profiles.last_name}` }}</td>
                    <td class="py-4 px-6 border-b border-grey-light">
                        <a class="edit-user" :href="`/uzytkownik/${payout_request.user_has_bonus.user.id}`">
                            <i class="fa-solid fa-user-pen"></i>
                        </a>
                    </td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.payout_value }}€</td>
                    <td class="py-4 px-6 border-b border-grey-light">
                        <span
                        :class="levelColor(payout_request.user_has_bonus.level_id)"><b>{{ level(levels,
                            payout_request.user_has_bonus.level_id).toUpperCase() }}</b></span>
                    </td>
                    <td class="py-4 px-6 border-b border-grey-light">{{ format(payout_request.created_at) }}</td>
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