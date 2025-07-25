<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import { ref } from 'vue';
import TableDefault from '@/Components/Templates/TableDefault.vue';
import Pagination from '@/Components/Navigation/Pagination.vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';

import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import MainContent from '@/Templates/HTML/MainContent.vue';

import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

import TableLink from '@/Templates/HTML/TableLink.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    levels: {
        type: Object,
        required: true
    },
    search_string: {
        type: String,
        default: ''
    },
    search_current_points: {
        type: [Number, String],
        default: ''
    },
    search_total_days: {
        type: [Number, String],
        default: ''
    },
    order_by: {
        type: String,
        default: 'full_name'
    },
    order: {
        type: String,
        default: 'asc'
    }
});

const users = ref(props.users);
const search_string = ref(props.search_string);
const search_current_points = ref(props.search_current_points);
const search_total_days = ref(props.search_total_days);
const order_by = ref(props.order_by);
const order = ref(props.order);

const paramsHasValues = () => {
    return search_string.value == '' && search_current_points.value == '' && search_total_days.value == ''
        ? false
        : true;
}

const order_icons = ref({
    up: 'fa-regular fa-circle-up',
    down: 'fa-regular fa-circle-down'
})

const changeOrder = (by) => {
    order_by.value = by;
    order.value = order.value == 'asc' ? 'desc' : 'asc';
    search();
}

const search = () => {
    let data = {};
    if (search_string.value != '') {
        data['search'] = search_string.value;
    }

    if (search_current_points.value != '') {
        data['current_points'] = search_current_points.value;
    }

    if (search_total_days.value != '') {
        data['total_days'] = search_total_days.value;
    }

    data['order_by'] = order_by.value;
    data['order'] = order.value;

    router.visit(route('users'), { method: 'get', data: data });
}

const url_params = () => {
    let url_params = [];

    if (search_string.value != '') {
        url_params.push(`search=${search_string.value}`);
    }

    if (search_current_points.value != '') {
        url_params.push(`current_points=${search_current_points.value}`);
    }

    if (search_total_days.value != '') {
        url_params.push(`total_days=${search_total_days.value}`);
    }

    url_params.push(`order_by=${order_by.value}`);
    url_params.push(`order=${order.value}`);

    return url_params.length == 0 ? '' : '&' + url_params.join('&');
}

const headers = [
    '#',
    '',
    'PESEL',
    'Imię i nazwisko',
    'punkty'
];

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('users')
    }
]

</script>

<template>
    <Head title="VeritasApp - użytkownicy" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>

            <div class="tw-flex tw-flex-col tw-items-center tw-gap-4 tw-mb-4 md:tw-flex-row">
                <v-text-field label="Szukaj" variant="solo-filled" placeholder="wpisz szukaną frazę" hide-details
                    class="tw-w-full" v-model="search_string" @keydown.enter="search()"></v-text-field>

                <v-text-field label="Punkty" variant="solo-filled" placeholder="wpisz ilość punktów" hide-details
                    class="tw-w-full focus:tw-ring-0" v-model="search_current_points" @keydown.enter="search()">
                    <template v-slot:prepend-inner><i class="tw-text-gray-400 fa-solid fa-trophy"></i></template>
                </v-text-field>

                <v-text-field label="Ilość dni" variant="solo-filled" placeholder="wpisz ilość punktów" hide-details
                    class="tw-w-full" v-model="search_total_days" @keydown.enter="search()">
                    <template v-slot:prepend-inner><i class="tw-text-gray-400 fa-solid fa-cloud-sun"></i></template>
                </v-text-field>

                <!-- <Transition mode="out-in">
                    <v-btn v-if="paramsHasValues()" variant="outlined"
                        @click="router.visit(route('users'), { method: 'get' })" class="shadow-lg" color="#991b1b">
                        <template v-slot:prepend><i class="w-4 h-4 fas fa-times-circle"></i></template>
                        Usuń filtry
                    </v-btn>
                    <v-btn v-else variant="outlined" @click="router.visit(route('users'), { method: 'get' })"
                        class="shadow-lg" color="#991b1b">
                        <template v-slot:prepend><i class="w-4 h-4 fas fa-times-circle"></i></template>
                        Usuń filtry
                    </v-btn>
                </Transition> -->
            </div>
            <div class="tw-flex tw-flex-row tw-justify-end tw-gap-2 tw-mb-4">
                <v-btn variant="outlined" @click="search()" class="tw-shadow-lg" color="#22c55e">
                    <template v-slot:prepend><i class="fa-solid fa-magnifying-glass"></i></template>
                    Szukaj
                </v-btn>
                <v-btn variant="outlined" @click="router.visit(route('users'), { method: 'get' })" class="tw-shadow-lg"
                    color="#991b1b" :disabled="!paramsHasValues()">
                    <template v-slot:prepend><i class="tw-w-4 tw-h-4 fas fa-times-circle"></i></template>
                    Usuń filtry
                </v-btn>
            </div>

            <NewPagination :pagination="users" :url_params_string="url_params()"></NewPagination>

            <v-card variant="tonal" :color="'white'" class="tw-mb-4 tw-shadow-2xl">
                <v-card-text class="!tw-p-0">
                    <div class="tw-overflow-x-auto" v-if="users.data.length > 0">
                        <v-data-table :items="users.data" height="auto" item-value="id">
                            <template #headers>
                                <tr class="tw-bg-gray-200">
                                    <th>#ID</th>
                                    <th class="!tw-text-center">AV</th>
                                    <th>PESEL</th>
                                    <th>Imię i nazwisko</th>
                                    <th class="!tw-text-center">Punkty</th>
                                    <th class="!tw-text-center">Łącznie dni</th>
                                    <th>Poziom</th>
                                    <th>Rekruter</th>
                                    <!-- <th class="!tw-text-center">Przedź do użytkownika</th> -->
                                </tr>
                            </template>
                            <template v-slot:item="{ item }">
                                <tr class="tw-text-xs">
                                    <td>#{{ item.id }}</td>
                                    <td class="!tw-py-1 tw-text-center">
                                        <div v-if="item.user_profile_image && item.user_profile_image.path && item.user_profile_image.status == 3"
                                            class="tw-relative tw-border-2 tw-border-gray-800 tw-shadow-xl profile-img profile-img-sm"
                                            :style="`background-image: url(user_profile_images/${item.user_profile_image.path});`">
                                        </div>
                                        <i v-else class="fa-solid fa-circle-user img-default img-default-sm"></i>
                                    </td>
                                    <td>{{ item.pesel }}</td>
                                    <td>
                                        <TableLink :url="`/uzytkownik/${item.id}`">
                                            {{ item.full_name }}
                                        </TableLink>
                                    </td>
                                    <td class="tw-text-center">{{ item.user_profiles.current_points ?? '-' }}</td>
                                    <td class="tw-text-center">{{ item.user_profiles.total_days ?? '-' }}</td>
                                    <td>
                                        <span :class="levelColor(item.user_profiles.level)">
                                            <b>
                                                {{ level(levels, item.user_profiles.level).toUpperCase() }}
                                            </b>
                                        </span>
                                    </td>
                                    <td>
                                        {{ `${item.user_profiles.recruiter_first_name ??
                                            '-'}${item.user_profiles.recruiter_last_name ?? ''}` }}
                                    </td>
                                    <!-- <td class="tw-text-center">
                                        <a :href="`/uzytkownik/${item.id}`">
                                            <i
                                                class="tw-text-lg tw-text-blue-500 fa-solid fa-user-pen hover:tw-text-blue-700"></i>
                                        </a>
                                    </td> -->
                                </tr>
                            </template>
                            <template #bottom></template>
                        </v-data-table>
                    </div>
                    <AlertInfo v-else title="" :show_icon="false">
                        Brak nowych wniosków o wypłatę
                    </AlertInfo>
                </v-card-text>
            </v-card>

            <NewPagination :pagination="users" :url_params_string="url_params()"></NewPagination>
        </MainContent>
    </AdminLayout>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.bounce-enter-active {
    animation: bounce-in 0.5s;
}

.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}

@keyframes bounce-in {
    0% {
        transform: scale(0);
    }

    50% {
        transform: scale(1.25);
    }

    100% {
        transform: scale(1);
    }
}
</style>
