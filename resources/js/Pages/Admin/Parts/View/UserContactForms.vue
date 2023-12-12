<script setup>
import { ref } from 'vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'contact.forms.user';
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
     <div class="bg-gray-100 shadow-xl rounded p-10">
        <h2 class="font-semibold text-2xl leading-tight text-center">
            <i class="fa-solid fa-comment text-blue-600"></i>
            Formularze kontaktowe
        </h2>
        <div v-if="forms.length <= 0" class="p-4 pt-0">
            <AlertInfo class="mt-10">Brak formularzy kontaktowych.</AlertInfo>
        </div>
        <div v-else>            
            <PaginationNoReload
                :pagination="data"
                class="mt-10"
                @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url"
                @reload-request-by-page-number="reload_by_page_number"
            ></PaginationNoReload>
            <div class="overflow-x-auto table-container" :class="data.total > data.per_page ? '' : 'mt-10'">
                <table class="text-center w-full border-collapse">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                #ID
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                Temat
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                Wiadomość
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Data utworzenia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(form, index) in forms">
                            <td class="py-4 px-6 border-b border-grey-light">{{ form.id }}</td>
                            <td class="py-4 px-6 border-b border-grey-light text-left">{{ form.subject }}</td>
                            <td class="py-4 px-6 border-b border-grey-light text-left">{{ form.msg }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(form.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>            
              
            <PaginationNoReload
                :pagination="data"
                class="mt-10"
                @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url"
                @reload-request-by-page-number="reload_by_page_number"
            ></PaginationNoReload>
        </div>
    </div>
</template>