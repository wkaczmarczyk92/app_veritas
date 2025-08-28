<script setup>

import { use_auth_mimic_store } from '@/Pinia/Admin/AuthMimicStore';
import { router } from '@inertiajs/vue3';

const auth_mimic_store = use_auth_mimic_store();

</script>

<template>
    <v-list-item link>
        <v-list-item-title>Testy</v-list-item-title>
        <template v-slot:append>
            <v-icon icon="mdi-menu-right" size="x-small"></v-icon>
        </template>
        <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
            <v-list>
                <v-list-item link v-for="mimic_user in $page.props.mimic_users" :key="mimic_user.id">
                    <v-list-item-title>
                        <div
                            :class="mimic_user.user_profiles.crt_id_caretaker ? 'tw-text-blue-600' : 'tw-text-orange-600'">
                            {{ mimic_user.user_profiles.full_name }}
                        </div>
                    </v-list-item-title>
                    <template v-slot:append>
                        <v-icon icon="mdi-menu-right" size="x-small"></v-icon>
                    </template>
                    <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                        <v-list>
                            <v-list-item link @click="auth_mimic_store.login_as_user(mimic_user.id)">
                                <v-list-item-title>Zaloguj jako</v-list-item-title>
                            </v-list-item>
                            <v-list-item link @click="router.get(route('user', { id: mimic_user.id }))">
                                <v-list-item-title>Profil</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-list-item>
</template>