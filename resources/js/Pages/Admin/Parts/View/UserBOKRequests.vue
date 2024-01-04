<script setup>

import { ref } from 'vue';
import axios from 'axios';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: {}
    }
})


const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'load.bok.requests.for.user';
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

</script>

<template>
    <AlertDanger v-model="pagination_alert.show" v-if="pagination_alert.show" :position_fixed="true">{{ pagination_alert.msg
    }}</AlertDanger>
    <div class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-mb-6 tw-grow">
        <div class="tw-px-6 tw-pt-6 tw-text-center">
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-inline-block tw-min-w-auto tw-max-w-full">
                <i class="fa-solid fa-triangle-exclamation tw-mr-2 tw-text-red-500"></i>
                Zgłoszenia do BOK-u
            </h1>
        </div>
        <div v-if="data && data.data && data.data.length <= 0" class="tw-p-4 tw-pt-0">
            <AlertInfo class="tw-mt-10">Brak zgłoszeń do BOK-u.</AlertInfo>
        </div>
        <div v-else>
            <PaginationNoReload :pagination="data" class="tw-mt-10 tw-ml-6" @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url" @reload-request-by-page-number="reload_by_page_number">
            </PaginationNoReload>
            <div class="tw-overflow-x-auto" :class="data.total > data.per_page ? '' : 'tw-mt-10'">
                <table class="tw-text-center tw-w-full tw-border-collapse">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                #
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light tw-text-left">
                                Temat
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light tw-text-left">
                                Wiadomość
                            </th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-lightt">
                                Utworzono
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:tw-bg-grey-lighter" v-for="(bok_request, index) in forms">
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ bok_request.id }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light tw-text-left">{{
                                bok_request.subject.subject }}
                            </td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light tw-text-left">{{ bok_request.msg }}
                            </td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ format(bok_request.created_at)
                            }}</td>
                        </tr>
                        <!-- More rows... -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
