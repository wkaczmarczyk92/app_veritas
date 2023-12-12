<script setup>

import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Icon from '@/Components/Functions/Icon';
import { ref, watch } from 'vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import axios from 'axios';
import { AlertStore } from '@/Pinia/AlertStore';
import NewPagination from '@/Components/Navigation/NewPagination.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';
import Tooltip from '@/Components/Templates/Tooltip.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    search: {
        type: [String, null],
        default: ''
    }
});

const data = ref(props.data);
const useAlertStore = AlertStore();

const username = (item) => {
    return `${item.user.user_profiles.first_name} ${item.user.user_profiles.last_name}`;
}

const phone = (item) => {
    return item.caretaker_phone_number ?? 'brak';
}

const caretaker_username = (item) => {
    if (!item.caretaker_first_name && !item.caretaker_last_name) {
        return 'brak';
    }

    let username = item.caretaker_first_name ?? '';
    username += username.length > 0 ? ' ' + (item.caretaker_last_name ?? '') : item.caretaker_last_name ?? '';

    return username;
}

const icon = ref(new Icon);

const for_action = ref([]);
const select_all = ref(false);
const disabled = ref(false);

watch([select_all, for_action], ([new_select, new_for_action], [old_select, old_for_action]) => {
    if (new_select != old_select) {
        toggle_all(select_all.value);
    }

    if (new_for_action.length <= 0) {
        select_all.value = false;
    }

    if (new_for_action != old_for_action) {
        console.log(new_for_action);
    }
})

const toggle_all = (select) => {
    if (!select) {
        for_action.value = [];
    } else {
        props.data.data.forEach(item => {
            if (!for_action.value.includes(item) && !item.locked) {
                for_action.value.push(item);
            }
        })
    }
}

async function set_as_complete() {
    if (for_action.value.length == 0) {
        useAlertStore.pushAlert('danger', 'Wybierz polecenia które chcesz oznaczyć jako wypłacone.');
        return;
    }

    disabled.value = true;
    
    await axios.patch(route('caretaker.recommendations.update.bonus.payout'), { items: for_action.value })
        .then(response => {
            if (!response.data.success) {
                useAlertStore.pushAlert('danger', response.data.msg);
                return;
            }

            if (response.data.success) {
                select_all.value = false;
                for_action.value = [];

                let index;

                response.data.data.forEach((item, index) => {
                    index = data.value.data.findIndex(obj => obj.id === item.id);
                    data.value.data[index] = item;
                });

                useAlertStore.pushAlert('success', 'Wybrane polecenia zostały pomyślnie odznaczone.');
            }

            disabled.value = false;
        });
}

const search = () => {
    router.visit(route('caretaker.recommendations.index'), {
        method: 'get',
        data: {
            search: search_string.value
        }
    });
}

const search_string = ref(props.search);

const tooltips = ref([])

for (let i = 0; i < Object.keys(data.value.data).length; i++) {
    tooltips.value[i] = {
        bonus: false,
        locked: false,
        open: false,
        pending: false,
        ready_to_payout: false,
        completed: false
    }
}


</script>


<template>
    <Head title="VeritasApp - polecenia opiekunek" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Polecenia opiekunek</h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 px-4 lg:px-8">
                <div class="flex flex-col md:flex-row h-full mb-4">
                    <div class="relative w-full md:w-1/3">
                        <input v-model="search_string" @change="search()" class="shadow-md block w-full py-2 pl-10 pr-4 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:placeholder-gray-500" type="text" placeholder="Szukaj">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <div v-if="search_string" class="font-pro h-full my-auto md:ml-3 mt-2 md:mt-0">
                        <button @click="router.visit(route('caretaker.recommendations.index'), { method: 'get' })" class="flex items-center justify-center px-2 py-2 border border-red-500 rounded-full text-sm font-medium text-white bg-red-500 hover:bg-red-600 h-full hover:border-red-600">
                            <i class="fas fa-times-circle mr-1 h-4 w-4 flex-shrink-0"></i>
                            <span>Usuń filtry</span>
                        </button>
                    </div>
                </div>
                <div v-if="data.data.length > 0">
                    <NewPagination
                        :pagination="data"
                        page_name="/polecenia-opiekunek"
                    ></NewPagination>
                    <div class="bg-gray-100 overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                            <div class="flex flex-row mt-6 text-gray-800 px-3 mb-3 pt-2">
                                <div class="flex">
                                    <i class="fa-regular fa-arrow-turn-down fa-flip-horizontal mr-2 mt-auto mb-auto"></i> 
                                    <div class="flex items-center mr-3">
                                        <input type="checkbox" id="for_all" v-model="select_all" class="h-4 w-4 rounded border-gray-400 text-gray-800 focus:ring-0">
                                    </div>
                                    <label for="for_all">Z zaznaczonymi:</label>
                                </div>
                                <div class="ml-4 underline text-green-600 hover:text-green-800 hover:cursor-pointer" @click="set_as_complete" :class="disabled ? 'pointer-events-none' : ''">
                                    oznacz jako Wypłacono bonus
                                </div>
                            </div>
                        <!-- <div class="overflow-x-auto"> -->
                            <table class="text-center w-full border-collapse">
                                <thead>
                                    <tr class="text-white text-xs">
                                        <th
                                            colspan="4"
                                            class="py-4 px-2 font-bold uppercase text-grey-dark border-b border-grey-light bg-blue-700">
                                            Osoba polecająca</th>
                                        <th
                                            colspan="3"
                                            class="py-4 px-2 bg-teal-700 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Osoba polecana</th>
                                        <th
                                            colspan="3"
                                            class="py-4 px-2 bg-purple-800 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Dodatkowe informacje</th>
                                    </tr>
                                    <tr class="text-white text-xs">
                                        <th
                                            class="py-4 px-2 bg-blue-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            </th>
                                        <th
                                            class="py-4 px-2 bg-blue-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            #</th>
                                        <th
                                            class="py-4 px-2 bg-blue-300 font-bold uppercase text-grey-dark border-b bg-blue-500">
                                            Imię i nazwisko</th>
                                        <th
                                            class="py-4 px-2 bg-blue-300 font-bold uppercase text-grey-dark border-b bg-blue-500">
                                            Przejdź do użytkownika</th>
                                        <th
                                            class="py-4 px-2 bg-teal-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Imię i nazwisko</th>
                                        <th
                                            class="py-4 px-2 bg-teal-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Nr telefonu</th>
                                        <th
                                            class="py-4 px-2 bg-teal-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            E-mail</th>
                                        <th
                                            class="py-4 px-2 bg-purple-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Gotowe do wypłaty?</th>
                                        <th
                                            class="py-4 px-2 bg-purple-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Edytuj</th>
                                        <th
                                            class="py-4 px-2 bg-purple-500 font-bold uppercase text-grey-dark border-b border-grey-light">
                                            Data utworzenia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover:bg-grey-lighter" v-for="(item, index) in data.data">
                                        <td class="py-4 px-6 border-b border-grey-light">
                                            <div class="flex items-center">
                                                <div v-if="item.bonus_payout_completed" class="relative">
                                                    <Tooltip v-if="tooltips[index].bonus">Bonus został wypłacony</Tooltip>
                                                    <i @mouseover="tooltips[index].bonus = true" @mouseleave="tooltips[index].bonus = false" class="fa-solid fa-square-check fa-lg text-green-600"></i>
                                                </div>
                                                <div v-else-if="item.ready_to_payout">
                                                    <input type="checkbox" v-model="for_action" :value="item" class="h-4 w-4 rounded border-gray-400 text-gray-800 focus:ring-0">
                                                </div>
                                                <div v-else-if="item.locked" class="relative">
                                                    <Tooltip v-if="tooltips[index].locked" class="absolute">Dane uzupełnione. Oczekuje na odpowiedź z CRM.</Tooltip>
                                                    <i @mouseover="tooltips[index].locked = true" @mouseleave="tooltips[index].locked = false"  class="fa-solid fa-lock-keyhole text-indigo-700 text-xl"></i>
                                                </div>
                                                <div v-else class="relative">
                                                    <Tooltip v-if="tooltips[index].open" class="absolute">Dane czekają na uzupełnienie.</Tooltip>
                                                    <i @mouseover="tooltips[index].open = true" @mouseleave="tooltips[index].open = false"  class="fa-solid fa-lock-open text-indigo-300 text-xl"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- <td class="py-4 px-6 border-b border-grey-light">{{ index + 1 + ((data.current_page - 1) * data.per_page) }}</td> -->
                                        <td class="py-4 px-6 border-b border-grey-light">{{ item.id }}</td>
                                        <td class="py-4 px-6 border-b border-grey-light">{{ username(item) }}</td>
                                        <td class="py-4 px-6 border-b border-grey-light">
                                            <a class="edit-user" :href="`/uzytkownik/${item.user.id}`">
                                                <i class="fa-solid fa-user-pen text-xl"></i>
                                            </a>
                                        </td>
                                        <td class="py-4 px-6 border-b border-grey-light">{{ caretaker_username(item) }}</td>
                                        <td class="py-4 px-6 border-b border-grey-light">{{ phone(item) }}</td>
                                        <td class="py-4 px-6 border-b border-grey-light">{{ item.caretaker_email ?? 'brak' }}</td>
                                        <td class="py-4 px-6 border-b border-grey-light" :class="icon._rpt_class(item.bonus_payout_completed, item.ready_to_payout)" v-html="icon._rpt(item.bonus_payout_completed)">
                                        </td>
                                        <td class="py-4 px-6 border-b border-grey-light">
                                            <a class="edit-user" :href="`/polecenia-opiekunek/${item.id}`">
                                                <i class="fa-solid fa-file-pen text-xl"></i>
                                            </a>
                                        </td>
                                        <td class="py-4 px-6 border-b border-grey-light">{{ format(item.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <NewPagination
                        class="mt-4"
                        :pagination="data"
                        page_name="/polecenia-opiekunek"
                    ></NewPagination>
                </div>
                <div v-else>
                    <StaticInfoAlert>Brak poleceń opiekunek.</StaticInfoAlert>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>