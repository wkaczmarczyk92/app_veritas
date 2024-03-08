<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';

const props = defineProps({
    last_logins: {
        type: Object,
        default: {}
    }
});

console.log(props.last_logins);
const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Ostatnie logowania',
        disabled: true,
    }
]

</script>

<template>
    <Head :title="`VeritasApp - wszystkie posty`" />
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
            <div class="tw-px-4 sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-px-6 tw-pt-16 tw-pb-8 tw-bg-gray-100 tw-rounded tw-shadow-xl sm:tw-rounded-lg sm:tw-px-20 sm:tw-pb-12">
                    <NewPagination :pagination="last_logins" page_name="/ostatnie-logowania"></NewPagination>
                    <table class="tw-w-full tw-text-center tw-border-collapse">
                        <thead>
                            <tr class="table-tr">
                                <th
                                    class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                    Imię i nazwisko</th>
                                <th
                                    class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                    Data i godzina logowania</th>
                                <!-- <th
                                    class="px-2 py-4 text-sm font-bold uppercase border-b bg-grey-lightest text-grey-dark border-grey-light">
                                    Przejdź do użytkownika</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:tw-bg-grey-lighter" v-for="(item, index) in last_logins.data">
                                <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ item.login_count ?? '-' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ item.date }}</td>
                                <!-- <td class="px-6 py-4 border-b border-grey-light">
                                    <a class="edit-user" :href="`/uzytkownik/${item.user.id}`">
                                        <i class="text-xl fa-solid fa-user-pen"></i>
                                    </a>
                                </td> -->
                            </tr>
                            <!-- More rows... -->
                        </tbody>
                    </table>




                </div>
            </div>
        </div>

    </AdminLayout>
</template>


<!-- <section class="mt-10 overflow-hidden bg-gray-100 rounded shadow-xl sm:rounded-lg"> -->
