<script setup>

import AlertInfo from '@/Components/Functions/AlertInfo.vue';

defineProps({
    users: {
        type: Object,
        required: true
    }
})

</script>
<template>
    <div class="bg-gray-100 shadow-xl rounded sm:p-10 p-3 py-6">
        <div class="font-semibold text-xl leading-tight flex flex-col sm:flex-row justify-between mb-4">
            <div>
                Ostatnio dodani użytkownicy
            </div>
            <div class="mb-1">
                <a :href="route('users')" class="text-blue-600 underline text-sm">
                    Zobacz wszystkich
                    <i class="fa-solid fa-user-magnifying-glass ml-1"></i>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto" v-if="users.length > 0">
            <table class="text-center w-full border-collapse text-xs">
                <thead>
                    <tr class="table-tr">
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            PESEL</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            Imię i nazwisko
                        </th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            Rekruter</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                            </th>
                    </tr>
                </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(user, index) in users">
                            <td class="py-4 px-6 border-b border-grey-light">{{ user.pesel }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ user.user_profiles.first_name }} {{ user.user_profiles.last_name }}</td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ `${user.user_profiles.recruiter_first_name ?? '-'} ${user.user_profiles.recruiter_last_name ?? ''}` }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">
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
    </div>
</template>