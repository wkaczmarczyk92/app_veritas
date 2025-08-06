<script setup>

import { use_auth_mimic_store } from '@/Pinia/Admin/AuthMimicStore';
import { usePage } from '@inertiajs/vue3'


const auth_mimic_store = use_auth_mimic_store();
console.log('page', usePage().props)

</script>

<template>
    <div class="tw-bg-gray-900 tw-p-1" v-if="$page.props.mimic_uuid">
        <v-btn color="#ea580c" @click="auth_mimic_store.back_to_admin_panel()">Powrót do panelu admina</v-btn>
    </div>
</template>