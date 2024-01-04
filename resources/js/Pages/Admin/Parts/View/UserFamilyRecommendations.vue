<script setup>

import { ref } from 'vue';
import axios from 'axios';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import { defaultTable } from '@/Pinia/StoreDefaultTable';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';
import Icon from '@/Components/Functions/Icon';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'family.recommendations.user';
default_data_getter.options = { user_id: props.user.id };

const data = ref({});
const forms = ref({});

const reload_by_url = async (url) => {
    data.value = await default_data_getter.reload_request_by_url(url);
    forms.value = data.value.data;

    console.log(data.value);
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

const icon = ref(new Icon);

</script>

<template>
    <AlertDanger v-model="pagination_alert.show" v-if="pagination_alert.show" :position_fixed="true">{{ pagination_alert.msg
    }}</AlertDanger>
    <div class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-p-10">
        <h2 class="tw-font-semibold tw-text-2xl tw-leading-tight tw-text-center">
            <i class="fa-solid fa-family tw-text-teal-600"></i>
            Polecenia rodzin
        </h2>
        <div v-if="forms.length <= 0" class="tw-p-4 tw-pt-0">
            <AlertInfo class="tw-mt-10">Brak poleconych rodzin.</AlertInfo>
        </div>
        <div v-else>
            <PaginationNoReload :pagination="data" class="tw-mt-10" @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url" @reload-request-by-page-number="reload_by_page_number">
            </PaginationNoReload>
            <div class="tw-overflow-x-auto table-container" :class="data.total > data.per_page ? '' : 'tw-mt-10'">
                <table class="tw-text-center tw-w-full tw-border-collapse">
                    <thead>
                        <tr class="table-tr tw-text-xs">
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light">
                                #ID
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light tw-text-left">
                                Nazwisko rodziny
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light tw-text-left">
                                Numer telefonu
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Dodatkowe informacje
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Wypłacono bonus?
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Data utworzenia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter text-xs" v-for="(form, index) in forms">
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ form.id }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light tw-text-left">{{
                                form.family_last_name }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light tw-text-left">+{{ form.country_code
                                + form.phone_number }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ form.info }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light"
                                v-html="icon.bonus_payout(form.bonus_payout_completed)"></td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ format(form.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- <PaginationNoReload
                :pagination="default_data_getter.data"
                class="mt-10"
                @show-alert="default_data_getter.show_pagination_alert"
                @reload-request-by-url="default_data_getter.reload_request_by_url"
                @reload-request-by-page-number="default_data_getter.reload_requst_by_page_number"
            ></PaginationNoReload> -->
        </div>
    </div>
</template>
