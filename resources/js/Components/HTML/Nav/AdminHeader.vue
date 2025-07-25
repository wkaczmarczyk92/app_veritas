<script setup>

import { use_auth_store } from '@/Pinia/AuthStore';
import { router } from '@inertiajs/vue3';
import SettingsMenu from './SettingsMenu.vue';
import MaintenanceMenu from './MaintenanceMenu.vue';
import { onBeforeMount } from 'vue'

const auth_store = use_auth_store()

onBeforeMount(async () => {
    await auth_store.get_auth_user()
})

</script>

<template>
    <div class="tw-bg-gray-900">
        <div class="tw-flex tw-items-center tw-text-gray-200 tw-px-4">
            <div class="tw-flex">
                <MaintenanceMenu />
                <SettingsMenu />
            </div>
            <div class="tw-flex tw-gap-2 tw-items-center tw-ml-auto">
                <v-menu location="bottom">
                    <template v-slot:activator="{ props }">
                        <v-btn color="#ea580c" rounded="0" v-bind="props" size="small">
                            {{ auth_store && auth_store.user ? auth_store.username() : 'ładowanie...' }}
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item @click="router.post(route('logout'))" :value="1">
                            <v-list-item-title>Wyloguj się</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>
        </div>
    </div>
</template>