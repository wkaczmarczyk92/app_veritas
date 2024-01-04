<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { format } from '@/Components/Functions/DateFormat.vue';
import { ref } from 'vue'
import Icon from '@/Components/Functions/Icon';
import TableDefault from '@/Components/Templates/TableDefault.vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});
const icon = ref(new Icon);
const username = (item) => {
    return `${item.user.user_profiles.first_name} ${item.user.user_profiles.last_name}`;
}
const phone = (item) => {
    return `+${item.country_code} ${item.phone_number}`;
}

const headers = [
    '#',
    'Imię i nazwisko osoby polecającej',
    'Przejdź do użytkownika',
    'Nazwisko rodziny',
    'Numer telefonu',
    'Dodatkowe informacje',
    'Wypłacono bonus?',
    'Powód odrzucenia',
    'Data utworzenia'
];

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Polecenia rodzin',
        disabled: true
    }
]

</script>


<template>
    <Head title="VeritasApp - polecenia rodzin" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <div class="py-12">
            <div class="max-w-full px-4 mx-auto sm:px-6 lg:px-8">
                <div v-if="data.data.length > 0">
                    <NewPagination :pagination="data" page_name="/polecenia-rodzin"></NewPagination>
                    <div class="mb-4 overflow-hidden bg-gray-100 shadow-xl">
                        <TableDefault :headers="headers">
                            <tr :class="item.rejected ? 'bg-red-200' : ''" v-for="(item, index) in data.data">
                                <td class="px-6 py-4 border-b border-grey-light">{{ index + 1 + ((data.current_page - 1) *
                                    data.per_page) }}</td>
                                <td class="px-6 py-4 border-b border-grey-light">{{ username(item) }}</td>
                                <td class="px-6 py-4 border-b border-grey-light">
                                    <a class="edit-user" :href="`/uzytkownik/${item.user.id}`">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </a>
                                </td>
                                <td class="px-6 py-4 border-b border-grey-light">
                                    {{ item.rejected ? 'dane usunięto' : item.family_last_name }}
                                </td>
                                <td class="px-6 py-4 border-b border-grey-light">{{ item.rejected ? 'dane usunięto' :
                                    phone(item) }}</td>
                                <td class="px-6 py-4 border-b border-grey-light">{{ item.rejected ? 'dane usunięto' :
                                    (item.info ?? '-') }}</td>

                                <td v-if="!item.rejected" class="px-6 py-4 border-b border-grey-light"
                                    v-html="icon.bonus_payout(item.bonus_payout_completed)">
                                </td>
                                <td v-else class="px-6 py-4 border-b border-grey-light">dane usunięto
                                </td>

                                <td class="px-6 py-4 border-b border-grey-light">{{ item.rejected_text ?? '-' }}</td>
                                <td class="px-6 py-4 border-b border-grey-light">{{ format(item.created_at) }}</td>
                            </tr>
                        </TableDefault>
                    </div>
                    <NewPagination :pagination="data" page_name="/polecenia-rodzin"></NewPagination>
                </div>
                <div v-else>
                    <StaticInfoAlert>Brak poleceń rodzin.</StaticInfoAlert>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
