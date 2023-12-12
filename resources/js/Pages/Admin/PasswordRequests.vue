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

</script>

<template>
    <Head title="VeritasApp - wnioski o wypłatę" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Zgłoszenia zmiany hasła</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100">
                    <TableDefault
                        v-if="password_requests.length > 0"
                        :headers="headers">
                            <tr class="hover:bg-grey-lighter" v-for="(password_request, index) in password_requests">
                                <td class="py-4 px-6 border-b border-grey-light">{{ password_request.id }}</td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ password_request.user.user_profiles.first_name }}</td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ password_request.user.user_profiles.last_name }}</td>
                                <td class="py-4 px-6 border-b border-grey-light">
                                    <a class="edit-user" :href="`/uzytkownik/${password_request.user.id}`">
                                        <i class="fa-solid fa-user-pen text-xl"></i>
                                    </a>
                                </td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ format(password_request.created_at) }}</td>
                            </tr>
                    </TableDefault>
                    <StaticInfoAlert v-else>Brak zgłoszeń o zmianę hasła.</StaticInfoAlert>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
