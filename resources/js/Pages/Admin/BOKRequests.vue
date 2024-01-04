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

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Zgłoszenia BOK',
        disabled: true
    }
]

</script>

<template>
    <Head title="VeritasApp - ustawienia punktów i kwoty wypłaty" />
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
            <div class="tw-px-4 tw-mx-auto tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                <NewPagination :pagination="bok_requests" page_name="/zgloszenia-do-boku"></NewPagination>

                <div class="tw-my-6 tw-bg-gray-100 tw-rounded tw-shadow-xl">
                    <TableDefault :headers="headers" v-if="bok_requests.data.length > 0">
                        <tr class="hover:bg-grey-lighter" v-for="(bok_request, index) in bok_requests.data">
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ bok_request.id }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{
                                `${bok_request.user.user_profiles.first_name} ${bok_request.user.user_profiles.last_name}`
                            }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">
                                <a class="edit-user" :href="`/uzytkownik/${bok_request.user.id}`">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ bok_request.subject.subject }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ bok_request.msg }}</td>
                            <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ format(bok_request.created_at)
                            }}</td>
                        </tr>
                    </TableDefault>
                    <StaticInfoAlert v-else>Brak danych do wyświetlenia.</StaticInfoAlert>
                </div>
                <NewPagination :pagination="bok_requests" page_name="/zgloszenia-do-boku"></NewPagination>

            </div>
        </div>
    </AdminLayout>
</template>
