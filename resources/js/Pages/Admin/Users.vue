<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import { ref } from 'vue';
import TableDefault from '@/Components/Templates/TableDefault.vue';
import Pagination from '@/Components/Navigation/Pagination.vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';


import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

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
    'Zdjęcie profilowe',
    'PESEL',
    'Imię i nazwisko'
];

</script>

<template>
    <Head title="VeritasApp - użytkownicy" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Użytkownicy</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 px-4 lg:px-8">
                <div class="flex flex-col md:flex-row h-full mb-4">
                    <div class="relative w-full md:w-1/3">
                        <input v-model="search_string" @change="search()" class="shadow-md block w-full py-2 pl-10 pr-4 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:placeholder-gray-500" type="text" placeholder="Szukaj">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <div class="relative w-auto md:ml-2 mt-2 md:mt-0">
                        <input v-model="search_current_points" @change="search()" class="shadow-md block w-full py-2 pl-10 pr-4 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:placeholder-gray-500" type="text" placeholder="Punkty">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-trophy text-gray-400"></i>
                        </div>
                    </div>
                    <div class="relative w-auto md:ml-2 mt-2 md:mt-0">
                        <input v-model="search_total_days" @change="search()" class="shadow-md block w-full py-2 pl-10 pr-4 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:placeholder-gray-500" type="text" placeholder="Ilość dni">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-cloud-sun text-gray-400"></i>
                        </div>
                    </div>
                    <div v-if="paramsHasValues()" class="font-pro h-full my-auto md:ml-3 mt-2 md:mt-0">
                        <button @click="router.visit(route('users'), { method: 'get' })" class="flex items-center justify-center px-2 py-2 border border-red-500 rounded-full text-sm font-medium text-white bg-red-500 hover:bg-red-600 h-full hover:border-red-600">
                            <i class="fas fa-times-circle mr-1 h-4 w-4 flex-shrink-0"></i>
                            <span>Usuń filtry</span>
                        </button>
                    </div>
                </div>

                <NewPagination
                    :pagination="users"
                    :url_params_string="url_params()"
                ></NewPagination>

                <div class="bg-gray-100 shadow-xl rounded my-6">
                    <div class="overflow-x-auto" v-if="users.data.length > 0">
                        <table class="text-center w-full border-collapse">
                            <thead>
                                <tr class="table-tr">
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        #</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        #</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        PESEL</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        <span class="order-by" @click="changeOrder('full_name')">Imię i Nazwisko <i v-if="order_by == 'full_name'" :class="order == 'asc' ? order_icons.up : order_icons.down"></i></span>
                                    </th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        <i class="fa-solid fa-trophy"></i> Punkty</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        Łącznie dni</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        Poziom</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        Rekruter</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        Przejdź do użytkownika</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-grey-lighter" v-for="(user, index) in users.data">
                                    <td class="py-4 px-6 border-b border-grey-light">{{ index + 1 + ((users.current_page - 1) * users.per_page) }}</td>
                                    <td class="py-4 px-6 border-b border-grey-light">
                                        <div v-if="user.user_profile_image && user.user_profile_image.path && user.user_profile_image.status == 3" class="profile-img profile-img-sm border-2 border-gray-800 shadow-xl relative" :style="`background-image: url(user_profile_images/${user.user_profile_image.path});`"></div>
                                        <i v-else class="fa-solid fa-circle-user img-default img-default-sm"></i>
                                    </td>
                                    <td class="py-4 px-6 border-b border-grey-light">{{ user.pesel }}</td>
                                    <td class="py-4 px-6 border-b border-grey-light">{{ user.full_name }}</td>
                                    <td class="py-4 px-6 border-b border-grey-light">{{ user.user_profiles.current_points ?? '-' }}</td>
                                    <td class="py-4 px-6 border-b border-grey-light">{{ user.user_profiles.total_days ?? '-' }}</td>
                                    <td class="py-4 px-6 border-b border-grey-light">
                                        <span :class="levelColor(user.user_profiles.level)">
                                            <b>
                                                {{ level(levels, user.user_profiles.level).toUpperCase() }}
                                            </b>
                                        </span>
                                    
                                    </td>
                                        <td class="py-4 px-6 border-b border-grey-light">{{ `${user.user_profiles.recruiter_first_name ?? '-'} ${user.user_profiles.recruiter_last_name ?? ''}` }}</td>
                                    <td class="py-4 px-6 border-b border-grey-light">
                                        <a class="edit-user" :href="`/uzytkownik/${user.id}`">
                                            <i class="fa-solid fa-user-pen text-xl"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                        </table>
                    </div>
                    <StaticInfoAlert v-else>Brak danych do wyświetlenia.</StaticInfoAlert>
                </div>

                <NewPagination
                    :pagination="users"
                    :url_params_string="url_params()"
                ></NewPagination>

            </div>
        </div>
    </AdminLayout>
</template>
