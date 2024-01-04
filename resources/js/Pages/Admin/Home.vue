<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

import LatestCreatedUsers from './Parts/View/LatestCreatedUsers.vue';
// import CurrentSettings from './Parts/View/CurrentSettings.vue';
import LatestPayoutRequests from './Parts/View/LatestPayoutRequests.vue';
import LatestBOKRequests from './Parts/View/LatestBOKRequests.vue';

import Grid4 from '@/Templates/Layout/Grid/Grid4.vue';
import Grid3 from '@/Templates/Layout/Grid/Grid3.vue';
import Card from '@/Templates/Layout/Card.vue';

import CheckpointBox from '@/Pages/Admin/Dashboard/Checkpoint.box.vue';
import BonusesBox from '@/Pages/Admin/Dashboard/Bonuses.box.vue';
import LastLoginBox from '@/Pages/Admin/Dashboard/Last.login.box.vue';
import LatestOffers from '@/Pages/Admin/Dashboard/Offer.latest.vue';

const props = defineProps({
    users: {
        type: Object
    },
    levels: {
        type: Object
    },
    latest_payout_requests: {
        type: Object,
        default: {}
    },
    latest_bok_request: {
        type: Object,
        default: {}
    },
    last_logins: {
        type: [Array, Object],
        default: []
    },
    latest_offers: {
        type: Object,
        required: true
    }
});

console.log(props.latest_offers)

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: true
    },
    {
        title: 'Pulpit',
        disabled: false,
        href: route('dashboard')
    }
]

</script>

<template>
    <Head title="VeritasApp - pulpit" />

    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <Grid4>
                    <CheckpointBox :levels="levels"></CheckpointBox>
                    <BonusesBox :levels="levels"></BonusesBox>
                    <LastLoginBox :last_logins="last_logins"></LastLoginBox>
                </Grid4>

                <Grid3>
                    <LatestCreatedUsers :users="users">
                    </LatestCreatedUsers>
                    <LatestOffers :offers="latest_offers"></LatestOffers>
                    <div class="tw-row-span-2">
                        <LatestPayoutRequests :latest_payout_requests="latest_payout_requests">
                        </LatestPayoutRequests>

                        <LatestBOKRequests :latest_bok_request="latest_bok_request">
                        </LatestBOKRequests>
                    </div>
                </Grid3>
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
