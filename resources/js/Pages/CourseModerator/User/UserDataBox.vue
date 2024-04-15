<template>
    <v-card :loading="false" :title="username(user)" :subtitle="username(user)" class="!tw-p-4">
        <template v-slot:prepend>
            <i class="fa-solid fa-user tw-text-2xl"></i>
        </template>
        <template v-slot:title class="tw-opacity-100">
            <div class="tw-flex tw-flex-col md:tw-flex-row lg:tw-flex-col xl:tw-flex-row tw-justify-between">
                <div>{{ username(user) }}</div>
                <div class="tw-flex tw-flex-row tw-gap-1">
                    <v-btn variant="outlined" color="#0284c7" size="small" @click="" :disabled="false">
                        <i class="fa-solid fa-user-pen tw-mr-2"></i>
                        Edytuj
                    </v-btn>
                    <v-btn v-if="crm_id == null" variant="outlined" color="#c026d3" size="small"
                        @click="connect_to_crm()" :disabled="false">
                        <i class="fa-solid fa-plug tw-mr-2"></i>
                        Połącz konto z CRM
                    </v-btn>
                </div>
            </div>
        </template>
        <template v-slot:subtitle class="tw-opacity-100">
            <div class="tw-flex tw-flex-row tw-gap-1 tw-mt-2 !tw-opacity-100 tw-flex-wrap">
                <RoleChip v-for="(role, index) in user.roles" :role="role" />
                <CompanyBranchChip v-for="(company_branch, index) in user.company_branches"
                    :company_branch="company_branch" />
            </div>
        </template>
        <v-card-text class="tw-flex tw-gap-1 tw-flex-col tw-my-6">
            <FlexData icon="fa-solid fa-envelope tw-text-lg" :value="user.email" />
            <FlexDataWithURL v-if="crm_id" icon="fa-solid fa-globe tw-text-lg" value="Profil rekrutera w CRM"
                :url="`https://local.grupa-veritas.pl/#/user/${crm_id}`" />
        </v-card-text>
    </v-card>
</template>

<script setup>

import FlexData from '@/Templates/HTML/Data/FlexData.vue'
import FlexDataWithURL from '@/Templates/HTML/Data/FlexDataWithURL.vue'

import { username } from '@/Composables/Username.js'
import { AlertStore } from '@/Pinia/AlertStore'

import CompanyBranchChip from '@/Composables/Chips/CompanyBranchChip.vue'
import RoleChip from '@/Composables/Chips/RoleChip.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    crm_id: {
        type: [null, Number],
        required: true
    }
})

const emit = defineEmits(['connect-to-crm'])

const alert_store = AlertStore()

const connect_to_crm = async () => {
    let response = await axios.post(route('course_moderator.user.crm_id.update', {
        id: props.user.id
    }), {
        email: props.user.email
    })

    response = response.data

    alert_store.pushAlert(response.alert_type, response.msg)

    if (response.success) {
        emit('connect-to-crm', response.crm_id)
    }
}

</script>

<style>
.v-card-subtitle {
    opacity: 1 !important;
}
</style>
