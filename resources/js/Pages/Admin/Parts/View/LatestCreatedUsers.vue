<script setup>

import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import Header from '@/Templates/HTML/Header.vue';

defineProps({
    users: {
        type: Object,
        required: true
    }
})

</script>
<template>
        <Header
            title="Ostatnio dodani użytkownicy"
            route_name="users"
            route_title="Zobacz wszystkich"
            icon="fa-solid fa-user-magnifying-glass"
        ></Header>
        <div class="overflow-x-auto" v-if="users.length > 0">
            <table class="text-center w-full border-collapse text-xs">
                <thead>
                    <tr class="table-tr">
                        <th
                            class="text-left py-2 pr-6 pl-1 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            PESEL</th>
                        <th
                            class="py-2 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            Imię i nazwisko
                        </th>
                        <th
                            class="py-2 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            Rekruter</th>
                        <th
                            class="py-2 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            </th>
                    </tr>
                </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(user, index) in users">
                            <td class="py-3 pr-6 pl-1 border-b border-grey-light">{{ user.pesel }}</td>
                            <td class="py-3 px-6 border-b border-grey-light">{{ user.user_profiles.first_name }} {{ user.user_profiles.last_name }}</td>
                                <td class="py-3 px-6 border-b border-grey-light">{{ `${user.user_profiles.recruiter_first_name ?? '-'} ${user.user_profiles.recruiter_last_name ?? ''}` }}</td>
                            <td class="py-3 pl-6 pr-1 border-b border-grey-light">
                                <a class="edit-user" :href="`/uzytkownik/${user.id}`">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
            </table>
        </div>
        <AlertInfo 
            v-else
            title=""
            :show_icon="false">
            Brak nowych użytkowników.
        </AlertInfo>
</template>