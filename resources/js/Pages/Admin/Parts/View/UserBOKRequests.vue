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
    <AlertDanger v-model="pagination_alert.show" v-if="pagination_alert.show" :position_fixed="true">{{ pagination_alert.msg }}</AlertDanger>
    <div class="bg-gray-100 shadow-xl rounded mb-6 grow">
        <div class="px-6 pt-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800 inline-block min-w-auto max-w-full">
                <i class="fa-solid fa-triangle-exclamation mr-2 text-red-500"></i>
                Zgłoszenia do BOK-u
            </h1>
        </div>
        <div v-if="data && data.data && data.data.length <= 0" class="p-4 pt-0">
            <AlertInfo class="mt-10">Brak zgłoszeń do BOK-u.</AlertInfo>
        </div>
        <div v-else>
            <PaginationNoReload
                :pagination="data"
                class="mt-10 ml-6"
                @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url"
                @reload-request-by-page-number="reload_by_page_number"
            ></PaginationNoReload>
            <div class="overflow-x-auto"  :class="data.total > data.per_page ? '' : 'mt-10'">
                <table class="text-center w-full border-collapse">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                #
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold text-left uppercase text-sm text-grey-dark border-b border-grey-light">
                                Temat
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold text-left uppercase text-sm text-grey-dark border-b border-grey-light">
                                Wiadomość
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Utworzono
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(bok_request, index) in forms">
                            <td class="py-4 px-6 border-b border-grey-light">{{ bok_request.id }}</td>
                            <td class="py-4 px-6 border-b border-grey-light text-left">{{ bok_request.subject.subject }}</td>
                            <td class="py-4 px-6 border-b border-grey-light text-left">{{ bok_request.msg }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(bok_request.created_at) }}</td>
                        </tr>
                        <!-- More rows... -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>