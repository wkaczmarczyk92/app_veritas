<script setup>

import { use_maintenance_store } from '@/Pinia/Admin/MaintenanceStore';

const maintenance_store = use_maintenance_store()

</script>

<template>
    <v-menu location="bottom">
        <template v-slot:activator="{ props }">
            <v-btn color="#ea580c" rounded="0" v-bind="props" size="small" variant="outlined">
                <div class="tw-text-sm">Status aplikacji: <i class="fas fa-circle"
                        :class="$page.props.maintenance_mode_status ? 'tw-text-red-600' : 'tw-text-green-600'"></i>
                </div>
            </v-btn>
        </template>

        <v-list v-if="$page.props.god_mode">
            <v-list-item v-if="!$page.props.maintenance_mode_status" @click="maintenance_store.down()" :value="1">
                <v-list-item-title>Wyłącz</v-list-item-title>
            </v-list-item>
            <v-list-item v-else @click="maintenance_store.up()" :value="2">
                <v-list-item-title>Włącz</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>