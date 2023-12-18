<script setup>

import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import Header from '@/Templates/HTML/Header.vue';
import Table from '@/Templates/HTML/Table.vue';

defineProps({
    users: {
        type: Object,
        required: true
    }
})

const headers = [
    'PESEL',
    'Imię i nazwisko',
    'Rekruter',
    ''
]

</script>
<template>
    <Header title="Ostatnio dodani użytkownicy" route_name="users" route_title="Zobacz wszystkich"
        icon="fa-solid fa-user-magnifying-glass"></Header>
    <div class="overflow-x-auto" v-if="users.length > 0">
        <Table :headers="headers">
            <tr class="hover:bg-grey-lighter" v-for="(user, index) in users">
                <td class="py-3 pl-1 pr-6 border-b border-grey-light">{{ user.pesel }}</td>
                <td class="px-6 py-3 border-b border-grey-light">{{ user.user_profiles.first_name }} {{
                    user.user_profiles.last_name }}</td>
                <td class="px-6 py-3 border-b border-grey-light">{{ `${user.user_profiles.recruiter_first_name ?? '-'}
                                    ${user.user_profiles.recruiter_last_name ?? ''}` }}</td>
                <td class="py-3 pl-6 pr-1 border-b border-grey-light">
                    <a class="edit-user" :href="`/uzytkownik/${user.id}`">
                        <i class="fa-solid fa-user-pen"></i>
                    </a>
                </td>
            </tr>
        </Table>
    </div>
    <AlertInfo v-else title="" :show_icon="false">
        Brak nowych użytkowników.
    </AlertInfo>
</template>
