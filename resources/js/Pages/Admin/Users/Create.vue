<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import { ref } from 'vue';

import MainContent from '@/Templates/HTML/MainContent.vue';
import { useUserStore } from '@/Pinia/UserStore';

const props = defineProps({
    roles: {
        type: Object,
        required: true
    }
});

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('users')
    },
    {
        title: 'Stwórz użytkownika',
        disabled: true,
        href: '#'
    }
]

const user_store = useUserStore()
user_store.new_user_init()

// const form_init = {
//     email: '',
//     first_name: '',
//     last_name: '',
//     role_id: '',
//     password: '',
//     password_confirmation: ''
// }

// const user = ref(form_init)

const show_password = ref(false)

</script>

<template>

    <Head title="VeritasApp - stwórz użytkownika" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <v-card title="Stwórz użytkownika">
            <v-card-text>
                <div class="tw-flex tw-flex-col tw-gap-6 tw-mt-6">
                    <div class="tw-flex tw-gap-4">
                        <v-text-field variant="outlined" label="E-mail" v-model="user_store.user.email" :error-messages="user_store?.errors?.email">
                            
                        </v-text-field>
                    </div>
                    <div class="tw-flex tw-gap-4">
                        <v-text-field variant="outlined" label="Imię" v-model="user_store.user.first_name" :error-messages="user_store?.errors?.first_name">
                            
                        </v-text-field>
                        <v-text-field variant="outlined" label="Nazwisko" v-model="user_store.user.last_name" :error-messages="user_store?.errors?.last_name">

                        </v-text-field>
                    </div>
                    <div class="tw-flex tw-gap-4">
                        <v-text-field class="tw-w-1/2" :type="show_password ? 'text' : 'password'" variant="outlined" label="Hasło" v-model="user_store.user.password" :error-messages="user_store?.errors?.password">
                            <template v-slot:append-inner>
                                <i v-if="!show_password" class="fa-solid fa-eye hover:tw-cursor-pointer" @click="show_password = !!!show_password"></i>
                                <i v-else class="fa-solid fa-eye-slash hover:tw-cursor-pointer" @click="show_password = !!!show_password"></i>
                            </template>
                        </v-text-field>
                        <v-text-field class="tw-w-1/2" :type="show_password ? 'text' : 'password'" variant="outlined" label="Powtórz hasło" v-model="user_store.user.password_confirmation">

                        </v-text-field>
                    </div>
                    <div class="tw-flex tw-gap-4">
                        <v-select variant="outlined" :items="roles" item-title="name_pl" item-value="id" label="Rola"  v-model="user_store.user.role_id" :error-messages="user_store?.errors?.role_id">

                        </v-select>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn variant="tonal" color="primary" @click="user_store.store()">Utwórz użytkownika</v-btn>
            </v-card-actions>
        </v-card>
    </AdminLayout>
</template>