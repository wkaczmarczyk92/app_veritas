<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import { format } from '@/Components/Functions/DateFormat.vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';
import TableDefault from '@/Components/Templates/TableDefault.vue';

const props = defineProps({
    bok_requests: {
        type: Object,
        required: true
    }
});

const bok_data = ref(props.bok_requests.data);

const headers = [
    '#',
    'Imię i nazwisko opiekunki',
    'Przejdź do użytkownika',
    'Temat',
    'Wiadomość',
    'Utworzono'
];

</script>

<template>
    <Head title="VeritasApp - ustawienia punktów i kwoty wypłaty" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Zgłoszenia do BOK-u</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 px-4 lg:px-8">
                <NewPagination 
                    :pagination="bok_requests"
                    page_name="/zgloszenia-do-boku"
                ></NewPagination>

                <div class="bg-gray-100 shadow-xl rounded my-6">
                    <TableDefault :headers="headers" v-if="bok_requests.data.length > 0">
                        <tr class="hover:bg-grey-lighter" v-for="(bok_request, index) in bok_requests.data">
                            <td class="py-4 px-6 border-b border-grey-light">{{ bok_request.id }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ `${bok_request.user.user_profiles.first_name} ${bok_request.user.user_profiles.last_name}` }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">
                                <a class="edit-user" :href="`/uzytkownik/${bok_request.user.id}`">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ bok_request.subject.subject }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ bok_request.msg }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(bok_request.created_at) }}</td>
                        </tr>
                    </TableDefault>
                    <StaticInfoAlert v-else>Brak danych do wyświetlenia.</StaticInfoAlert>
                </div>
                <NewPagination 
                    :pagination="bok_requests"
                    page_name="/zgloszenia-do-boku"
                ></NewPagination>

            </div>
        </div>
    </AdminLayout>
</template>
