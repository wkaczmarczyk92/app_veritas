<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PasswordRequestStore } from '@/Pinia/PasswordRequestStore';
import TableDefault from '@/Components/Templates/TableDefault.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

const usePasswordRequestStore = PasswordRequestStore();

const props = defineProps({
    password_requests: {
        type: Object,
        required: true
    }
});

console.log(props.password_requests);

const headers = [
    'ID',
    'Imię',
    'Nazwisko',
    'Przejdź do użytkownika',
    'Data wysłania zgłoszenia'
];

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Zgłoszenia zmiany hasła',
        disabled: true
    }
]

</script>

<template>
    <Head title="VeritasApp - zgłoszenia zmiany hasła" />
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
            <div class="tw-px-4 tw-mx-auto tw-max-w-full sm:tw-px-6 lg:tw-px-8">
                <div class="tw-bg-gray-100">
                    <TableDefault v-if="password_requests.length > 0" :headers="headers">
                        <tr class="hover:tw-bg-grey-lighter" v-for="(password_request, index) in password_requests">
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ password_request.id }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                password_request.user.user_profiles.first_name }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                password_request.user.user_profiles.last_name }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                <a class="edit-user" :href="`/uzytkownik/${password_request.user.id}`">
                                    <i class="text-xl fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                format(password_request.created_at) }}</td>
                        </tr>
                    </TableDefault>
                    <StaticInfoAlert v-else>Brak zgłoszeń o zmianę hasła.</StaticInfoAlert>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
