<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

import LatestCreatedUsers from './Parts/View/LatestCreatedUsers.vue';
import CurrentSettings from './Parts/View/CurrentSettings.vue';
import LatestPayoutRequests from './Parts/View/LatestPayoutRequests.vue';
import LatestBOKRequests from './Parts/View/LatestBOKRequests.vue';

const props = defineProps({
    users: {
        type: Object
    },
    levels: {
        type: Object
    },
    payout_cash: {
        type: Number
    },
    points_to_payout: {
        type: Number
    },
    latest_payout_requests: {
        type: Object,
        default: {}
    },
    latest_bok_request: {
        type: Object,
        default: {}
    },
    // point_chekpoints: {
    //     type: Object,
    //     defaul: {}
    // }
});

</script>

<template>
    <Head title="VeritasApp - pulpit" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Pulpit</h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8 px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                    <LatestCreatedUsers
                        :users="users">
                    </LatestCreatedUsers>

                    <CurrentSettings
                        :levels="levels"
                        :payout_cash="payout_cash"
                        :points_to_payout="points_to_payout"
                    ></CurrentSettings>

                    <div class="bg-gray-100 shadow-xl rounded sm:p-10 p-3 py-6">

                        <LatestPayoutRequests
                            :latest_payout_requests="latest_payout_requests">
                        </LatestPayoutRequests>

                        <LatestBOKRequests
                            :latest_bok_request="latest_bok_request">
                        </LatestBOKRequests>

                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
    hr {
        border-color: #0062c4;
        border-style: dotted;
        border-top: none;
        border-width: 5px;
    }
</style>
