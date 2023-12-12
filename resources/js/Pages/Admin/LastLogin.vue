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

</script>

<template>
    <Head :title="`VeritasApp - wszystkie posty`" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Ostatnie logowania</h2>
        </template>
        <div class="py-12">
            <div class="sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 shadow-xl rounded sm:rounded-lg px-6 sm:px-20 pt-16 pb-8 sm:pb-12">
                    <NewPagination
                        :pagination="last_logins"
                    ></NewPagination>
                    <table class="text-center w-full border-collapse">
                        <thead>
                            <tr class="table-tr">
                                <th
                                    class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Imię i nazwisko</th>
                                <th
                                    class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Data i godzina logowania</th>
                                <!-- <th
                                    class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Przejdź do użytkownika</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-grey-lighter" v-for="(item, index) in last_logins.data">
                                <td class="py-4 px-6 border-b border-grey-light">{{ item.login_count ?? '-' }}</td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ item.date }}</td>
                                <!-- <td class="py-4 px-6 border-b border-grey-light">
                                    <a class="edit-user" :href="`/uzytkownik/${item.user.id}`">
                                        <i class="fa-solid fa-user-pen text-xl"></i>
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


<!-- <section class="bg-gray-100 overflow-hidden shadow-xl rounded sm:rounded-lg mt-10"> -->