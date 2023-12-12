<script setup>

import axios from 'axios';
import { ref, watch } from 'vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: {}
    }
});

const usePayoutRequestStore = payoutRequestsStore();

const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'load.payout.requests.for.user';
default_data_getter.options = { id: props.user.id };

const data = ref({});
const forms = ref({});

const reload_by_url = async (url) => {
    data.value = await default_data_getter.reload_request_by_url(url);
    forms.value = data.value.data;
}

const reload_by_page_number = async (page) => {
    data.value = await default_data_getter.reload_requst_by_page_number(page);
    forms.value = data.value.data;
}

data.value = await default_data_getter.reload_requst_by_page_number(1);
forms.value = data.value.data;

const pagination_alert = ref({
    show: false,
    msg: ''
});

const show_pagination_alert = (msg) => {
    pagination_alert.value.show = true;
    pagination_alert.value.msg = msg;
}

const select_all = ref(false);
const payout_reuqests_for_action = ref([]);

async function set_as_complete() {
    await axios.patch(route('payoutrequests.update'), { to_update: payout_reuqests_for_action.value })
        .then(response => {
            reload_by_page_number(1);
            usePayoutRequestStore.countIncomplete();
            select_all.value = false;
            payout_reuqests_for_action.value = [];
        });
}

watch(
    select_all, () => {
        toggle_all(select_all.value);
    },
    payout_reuqests_for_action, () => {
        if (payout_reuqests_for_action.value.length <= 0) {
            select_all.value = false;
        }
    }
)

const toggle_all = (select) => {
    if (!select) {
        payout_reuqests_for_action.value = [];
    } else {
        forms.value.forEach(item => {
            if (!payout_reuqests_for_action.value.includes(item.id) && item.user_has_bonus.completed == 0) {
                payout_reuqests_for_action.value.push(item.id);
            }
        })
    }
}

const reset_all = () => {
    payout_reuqests_for_action.value = [];
    select_all.value = false;
}

const pulse = ref('bg-gradient-to-r from-red-700 via-red-500 to-red-600');

</script>

<template>
    <AlertDanger v-model="pagination_alert.show" v-if="pagination_alert.show" :position_fixed="true">{{ pagination_alert.msg }}</AlertDanger>
    <div class="bg-gray-100 shadow-xl rounded mb-6 grow">
        <div class="px-6 pt-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800 inline-block min-w-auto max-w-full">
                <i class="fa-light fa-sack-dollar mr-2 text-green-600"></i>
                Wnioski o wypłatę
            </h1>
        </div>
        
        <div v-if="data && data.data && data.data.length <= 0" class="p-4 pt-0">r
            <AlertInfo class="mt-10">Brak wniosków o wypłatę.</AlertInfo>
        </div>
        <div v-else>
            <PaginationNoReload
                :pagination="data"
                class="mt-10 ml-6"
                @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url"
                @reload-request-by-page-number="reload_by_page_number"
            ></PaginationNoReload>
            <div class="flex flex-row mt-6 text-gray-800 mb-3 px-6 pt-2">
                <div class="flex">
                    <i class="fa-regular fa-arrow-turn-down fa-flip-horizontal mr-2 mt-auto mb-auto"></i> 
                    <div class="flex items-center mr-3">
                        <input type="checkbox" id="for_all" v-model="select_all" class="h-4 w-4 rounded border-gray-400 text-gray-800 focus:ring-0">
                    </div>
                    <label for="for_all">Z zaznaczonymi:</label>
                </div>
                <div class="ml-4 underline text-green-600 hover:text-green-800 hover:cursor-pointer" @click="set_as_complete">
                    oznacz jako Zrealizowane
                </div>
            </div>
            <div class="overflow-x-auto table-container" :class="data.total > data.per_page ? '' : 'mt-10'">
                <table class="text-center w-full border-collapse">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                #ID
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Kwota bonusu
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Ilość użytych punktów
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Data utworzenia
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Data aktualizacji
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(payout_request, index) in forms" :class="payout_request.payment_completed == 0 ? pulse : ''">
                            <td class="py-4 px-6 border-b border-grey-light">
                                <div class="flex items-center">
                                    <input v-if="payout_request.user_has_bonus.completed == 0" type="checkbox" v-model="payout_reuqests_for_action" :value="payout_request.id" class="h-4 w-4 rounded border-gray-400 text-gray-800 focus:ring-0">
                                    <i v-else class="fa-solid fa-square-check fa-lg text-green-600"></i>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.id }}</td>
                            <!-- <td class="py-4 px-6 border-b border-grey-light">asdasd</td> -->
                            <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.payout_value }}€</td>
                            <!-- <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.used_points }}</td> -->
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(payout_request.created_at) }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.updated_at != null ? format(payout_request.updated_at) : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</template>