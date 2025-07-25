<script setup>

import axios from 'axios';
import { ref, watch } from 'vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';
import { level } from '@/Components/Functions/Level.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: {}
    }
});

const usePayoutRequestStore = payoutRequestsStore();

const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'payout.requests.user';
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
    await axios.patch(route('payout.requests.update'), { to_update: payout_reuqests_for_action.value })
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

const pulse = ref('tw-bg-gradient-to-r tw-from-red-700 tw-via-red-500 tw-to-red-600');

const set_card_color = (payout_request) => {
    if (Number(payout_request.user_has_bonus.status.id) == 2) {
        return '#22c55e'
    }

    if (payout_request.black_list_comment) {
        return '#dc2626'
    }

    return ''
}

</script>

<template>
    <div class="tw-p-2">
        <div v-if="data && data.data && data.data.length <= 0" class="">
            <v-alert color="info" class="tw-mt-4">Brak wniosków o wypłatę.</v-alert>
        </div>
        <div v-else>
            <div class="tw-flex tw-flex-col tw-gap-2">
                <v-card v-for="(payout_request, index) in forms" :key="index" :title="`Wniosek #${payout_request.id}`"
                    :color="set_card_color(payout_request)">
                    <v-card-text class="!tw-text-lg">
                        <div class="tw-flex tw-flex-col tw-gap-1">
                            <div class="tw-flex tw-items-center">
                                <div class="tw-w-1/3">Kwota bonusu</div>
                                <div class="tw-w-1/3">:</div>
                                <div class="tw-w-1/3">{{ payout_request.payout_value }}€</div>
                            </div>
                            <div class="tw-flex tw-items-center">
                                <div class="tw-w-1/3">Za poziom</div>
                                <div class="tw-w-1/3">:</div>
                                <div class="tw-w-1/3">{{ payout_request.user_has_bonus.level.name }}</div>
                            </div>
                            <div class="tw-flex tw-items-center">
                                <div class="tw-w-1/3">Wniosek utworzono</div>
                                <div class="tw-w-1/3">:</div>
                                <div class="tw-w-1/3">{{ format(payout_request.created_at) }}</div>
                            </div>
                            <div class="tw-flex tw-items-center">
                                <div class="tw-w-1/3">Status</div>
                                <div class="tw-w-1/3">:</div>
                                <div class="tw-w-1/3">{{ payout_request.user_has_bonus.status.name_pl }}</div>
                            </div>
                            <div v-if="payout_request.black_list_comment" class="tw-flex tw-items-center">
                                <div class="tw-w-1/3">Komentarz BL</div>
                                <div class="tw-w-1/3">:</div>
                                <div class="tw-w-1/3">{{ payout_request.black_list_comment }}</div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </div>
</template>
