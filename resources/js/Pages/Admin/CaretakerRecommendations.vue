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

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Polecenia opiekunek',
        disabled: true,
    }
]

</script>


<template>
    <Head title="VeritasApp - polecenia opiekunek" />

    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-4 tw-mx-auto sm:tw-px-6 tw-lg:px-8">
                <div class="tw-flex tw-flex-col tw-h-full tw-mb-4 md:tw-flex-row">
                    <div class="tw-relative tw-w-full md:tw-w-1/3">
                        <input v-model="search_string" @change="search()"
                            class="tw-block tw-w-full tw-py-2 tw-pl-10 tw-pr-4 tw-text-gray-900 tw-placeholder-gray-400 tw-border tw-border-gray-300 tw-rounded-full tw-shadow-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-border-blue-500 focus:tw-placeholder-gray-500"
                            type="text" placeholder="Szukaj">
                        <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-flex tw-items-center tw-pl-3">
                            <i class="tw-text-gray-400 fas fa-search"></i>
                        </div>
                    </div>
                    <div v-if="search_string" class="tw-h-full tw-my-auto tw-mt-2 tw-font-pro md:tw-ml-3 md:tw-mt-0">
                        <button @click="router.visit(route('caretaker.recommendations.index'), { method: 'get' })"
                            class="tw-flex items-center tw-justify-center tw-h-full tw-px-2 tw-py-2 tw-text-sm tw-font-medium tw-text-white tw-bg-red-500 tw-border tw-border-red-500 tw-rounded-full hover:tw-bg-red-600 hover:tw-border-red-600">
                            <i class="tw-flex-shrink-0 tw-w-4 tw-h-4 tw-mr-1 fas fa-times-circle"></i>
                            <span>Usuń filtry</span>
                        </button>
                    </div>
                </div>
                <div v-if="data.data.length > 0">
                    <NewPagination :pagination="data" page_name="/polecenia-opiekunek"></NewPagination>
                    <div class="tw-overflow-hidden tw-bg-gray-100 tw-shadow-xl">
                        <div class="tw-overflow-x-auto">
                            <div class="tw-flex tw-flex-row tw-px-3 tw-pt-2 tw-mt-6 tw-mb-3 tw-text-gray-800">
                                <div class="tw-flex">
                                    <i
                                        class="tw-mt-auto tw-mb-auto tw-mr-2 fa-regular fa-arrow-turn-down fa-flip-horizontal"></i>
                                    <div class="tw-flex tw-items-center tw-mr-3">
                                        <input type="checkbox" id="for_all" v-model="select_all"
                                            class="tw-w-4 tw-h-4 tw-text-gray-800 tw-border-gray-400 tw-rounded focus:tw-ring-0">
                                    </div>
                                    <label for="for_all">Z zaznaczonymi:</label>
                                </div>
                                <div class="tw-ml-4 tw-text-green-600 tw-underline hover:tw-text-green-800 hover:tw-cursor-pointer"
                                    @click="set_as_complete" :class="disabled ? 'tw-pointer-events-none' : ''">
                                    oznacz jako Wypłacono bonus
                                </div>
                            </div>
                            <!-- <div class="overflow-x-auto"> -->
                            <table class="tw-w-full tw-text-center tw-border-collapse">
                                <thead>
                                    <tr class="tw-text-xs tw-text-white">
                                        <th colspan="4"
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-blue-700 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Osoba polecająca</th>
                                        <th colspan="3"
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-teal-700 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Osoba polecana</th>
                                        <th colspan="3"
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-purple-700 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Dodatkowe informacje</th>
                                    </tr>
                                    <tr class="text-xs text-white">
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-blue-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                        </th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-blue-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            #</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-blue-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Imię i nazwisko</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-blue-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Przejdź do użytkownika</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-teal-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Imię i nazwisko</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-teal-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Nr telefonu</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-teal-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            E-mail</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-purple-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Gotowe do wypłaty?</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-purple-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Edytuj</th>
                                        <th
                                            class="tw-px-2 tw-py-4 tw-font-bold tw-uppercase tw-bg-purple-500 tw-border-b tw-text-grey-dark tw-border-grey-light">
                                            Data utworzenia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover:tw-bg-grey-lighter" v-for="(item, index) in data.data">
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                            <div class="tw-flex tw-items-center">
                                                <div v-if="item.bonus_payout_completed" class="tw-absolute">
                                                    <Tooltip v-if="tooltips[index].bonus">Bonus został wypłacony</Tooltip>
                                                    <i @mouseover="tooltips[index].bonus = true"
                                                        @mouseleave="tooltips[index].bonus = false"
                                                        class="tw-text-green-600 fa-solid fa-square-check fa-lg"></i>
                                                </div>
                                                <div v-else-if="item.ready_to_payout">
                                                    <input type="checkbox" v-model="for_action" :value="item"
                                                        class="tw-w-4 tw-h-4 tw-text-gray-800 tw-border-gray-400 tw-rounded focus:tw-ring-0">
                                                </div>
                                                <div v-else-if="item.locked" class="tw-relative">
                                                    <Tooltip v-if="tooltips[index].locked" class="tw-absolute">Dane
                                                        uzupełnione. Oczekuje na odpowiedź z CRM.</Tooltip>
                                                    <i @mouseover="tooltips[index].locked = true"
                                                        @mouseleave="tooltips[index].locked = false"
                                                        class="tw-text-xl tw-text-indigo-700 fa-solid fa-lock-keyhole"></i>
                                                </div>
                                                <div v-else class="tw-relative">
                                                    <Tooltip v-if="tooltips[index].open" class="tw-absolute">Dane czekają na
                                                        uzupełnienie.</Tooltip>
                                                    <i @mouseover="tooltips[index].open = true"
                                                        @mouseleave="tooltips[index].open = false"
                                                        class="tw-text-xl tw-text-indigo-300 fa-solid fa-lock-open"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ index + 1 + ((data.current_page - 1) * data.per_page) }}</td> -->
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ item.id }}</td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ username(item) }}
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                            <a class="edit-user" :href="`/uzytkownik/${item.user.id}`">
                                                <i class="tw-text-xl fa-solid fa-user-pen"></i>
                                            </a>
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                            caretaker_username(item) }}</td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ phone(item) }}</td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ item.caretaker_email
                                            ?? 'brak'
                                        }}</td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light"
                                            :class="icon._rpt_class(item.bonus_payout_completed, item.ready_to_payout)"
                                            v-html="icon._rpt(item.bonus_payout_completed)">
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                            <a class="edit-user" :href="`/polecenia-opiekunek/${item.id}`">
                                                <i class="tw-text-xl fa-solid fa-file-pen"></i>
                                            </a>
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                            format(item.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <NewPagination class="tw-mt-4" :pagination="data" page_name="/polecenia-opiekunek"></NewPagination>
                </div>
                <div v-else>
                    <StaticInfoAlert>Brak poleceń opiekunek.</StaticInfoAlert>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
