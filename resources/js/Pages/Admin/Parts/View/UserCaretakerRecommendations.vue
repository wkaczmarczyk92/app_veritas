<script setup>
import { ref } from 'vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import PaginationNoReload from '@/Components/Navigation/PaginationNoReload.vue';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';
import Icon from '@/Components/Functions/Icon';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'caretaker.recommendations.user';
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


const icon = ref(new Icon);

const username = (form) => {
    if (!form.caretaker_first_name && !form.caretaker_last_name) {
        return 'brak';
    }

    let username = form.caretaker_first_name ?? '';
    username += username.length > 0 ? ' ' + (form.caretaker_last_name ?? '') : form.caretaker_last_name ?? '';

    return username;
}

</script>


<template>
     <AlertDanger v-model="pagination_alert.show" v-if="pagination_alert.show" :position_fixed="true">{{ pagination_alert.msg }}</AlertDanger>
     <div class="bg-gray-100 shadow-xl rounded p-10">
        <h2 class="font-semibold text-2xl leading-tight text-center">
            <i class="fa-regular fa-user-group text-pink-600"></i>
            Polecenia opiekunek
        </h2>
        <div v-if="forms.length <= 0" class="p-4 pt-0">
            <AlertInfo class="mt-10">Brak poleconych opiekunek.</AlertInfo>
        </div>
        <div v-else>            
            <!-- <div> -->
            <PaginationNoReload
                :pagination="data"
                class="mt-10"
                @show-alert="show_pagination_alert"
                @reload-request-by-url="reload_by_url"
                @reload-request-by-page-number="reload_by_page_number"
            ></PaginationNoReload>
            <div class="overflow-x-auto table-container" :class="data.total > data.per_page ? '' : 'mt-10'">
            <!-- <div class="overflow-x-auto table-container mt-10"> -->
                <table class="text-center w-full border-collapse">
                    <thead>
                        <tr class="table-tr text-xs">
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                                #ID
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light text-left">
                                Imię i nazwisko
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                                E-mail
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                                Nr telefonu
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                                Przejdź do polecenia
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                                Wypłacono bonus?
                            </th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                                Data utworzenia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(form, index) in forms">
                            <td class="py-4 px-6 border-b border-grey-light">{{ form.id }}</td>
                            <td class="py-4 px-6 border-b border-grey-light text-left" v-html="username(form)"></td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ form.caretaker_email ?? '-' }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ form.caretaker_phone_number ?? '-' }}</td>
                            <!-- <td class="py-4 px-6 border-b border-grey-light">{{ form.caretaker_phone_number }}</td> -->
                            <td class="py-4 px-6 border-b border-grey-light">
                                <a class="edit-user" :href="`/polecenia-opiekunek/${form.id}`">
                                    <i class="fa-solid fa-file-pen text-xl"></i>
                                </a>
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light" v-html="icon.bonus_payout(form.bonus_payout_completed)"></td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(form.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>            
            <!-- <PaginationNoReload
                :pagination="data[0]"
                class="mt-10"
                @show-alert="showAlert"
                @reload-request-by-url="reloadRequestsByURL"
                @reload-request-by-page-number="reloadRequestsByPageNumber"
            ></PaginationNoReload> -->
        </div>
    </div>
</template>