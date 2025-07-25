<script setup>

import { format } from '@/Components/Functions/DateFormat.vue';
import { ref, onActivated } from 'vue';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';
import { AlertStore } from '@/Pinia/AlertStore';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import { use_processing_store } from '@/Pinia/ProcessingStore';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
})

const payout_requests = ref({});
const payout_request_store = payoutRequestsStore();

payout_requests.value = await payout_request_store.load('in_progress');
payout_request_store.payout_requests_in_progress = payout_requests.value[0]

const selected = ref([])
const headers = [
    {
        title: '#ID',
        value: 'id'
    },
    {
        title: 'Imię i nazwisko opiekunki',
        value: 'username'
    },
    {
        title: 'PESEL',
        value: 'user_has_bonus.user.pesel'
    },
    {
        title: 'Kwota bonusu',
        value: 'user_has_bonus.bonus_value'
    },
    {
        title: 'Za poziom',
        value: 'user_has_bonus.level_id'
    },
    {
        title: 'Ostatnia aktualizacja',
        value: 'created_at'
    }
];

const change_status = async (status_name) => {
    payout_requests.value = await payout_request_store.change_status(selected.value, status_name)
    selected.value = []
}

</script>

<template>
    <div
        v-if="payout_request_store.payout_requests_in_progress && payout_request_store.payout_requests_in_progress.length <= 0">
        <v-alert color="info" class="tw-mt-10">Brak nowych wniosków o wypłatę.</v-alert>
    </div>
    <div v-else>
        <Transition mode="out-in" name="fade">
            <div v-if="selected.length > 0">
                <div class="tw-flex tw-gap-2 tw-items-center">
                    <div>Z zaznaczonymi:</div>
                    <a href="#" class="tw-text-green-600 hover:tw-text-green-800 hover:tw-underline"
                        @click="change_status('completed')">oznacz jako "Zrealizowane"</a>
                    <a href="#" class="tw-text-purple-600 hover:tw-text-purple-800 hover:tw-underline"
                        @click="change_status('for_approval')">oznacz jako "Do akceptacji"</a>
                    <!-- <a href="#" class="tw-text-red-600 hover:tw-text-red-800 hover:tw-underline">usuń</a> -->
                </div>
            </div>
        </Transition>
        <v-data-table
            v-if="payout_request_store.payout_requests_in_progress && payout_request_store.payout_requests_in_progress.length"
            :headers="headers" :items="payout_request_store.payout_requests_in_progress" item-value="id"
            items-per-page="50" show-select v-model="selected">
            <template #item.username="{ item }">
                <a :href="route('user', item.user_has_bonus.user.id)"
                    class="tw-text-blue-600 hover:tw-underline hover:tw-text-blue-900">{{
                        item.user_has_bonus.user.user_profiles.full_name }}</a>
            </template>
            <!-- <template #item.level="{ item }">
                {{ item }}
            </template> -->
            <template #item.user_has_bonus.bonus_value="{ value }">
                {{ value }} €
            </template>
            <template #item.created_at="{ value }">
                {{ format(value) }}
            </template>
            <template #item.user_has_bonus.level_id="{ value }">
                <span :class="levelColor(value)"><b>{{ level(levels, value).toUpperCase() }}</b></span>
            </template>
        </v-data-table>
    </div>
</template>
