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

</script>

<template>
    <Head title="VeritasApp - pulpit" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Pulpit</h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8 px-4">
                <Grid4>
                    <Card>
                        <CheckpointBox :levels="levels"></CheckpointBox>
                    </Card>
                    <Card>
                        <BonusesBox :levels="levels"></BonusesBox>
                    </Card>
                    <Card class="border border-2 border-blue-500 bg-gray-300">
                        <LastLoginBox :last_logins="last_logins"></LastLoginBox>
                    </Card>
                </Grid4>

                <Grid3>
                    <Card>
                        <LatestCreatedUsers
                            :users="users">
                        </LatestCreatedUsers>
                    </Card>
                    <Card>
                        <LatestOffers :offers="latest_offers"></LatestOffers>
                    </Card>
                    <div class="row-span-2">
                        <Card>
                            <LatestPayoutRequests
                                :latest_payout_requests="latest_payout_requests">
                            </LatestPayoutRequests>
                        </Card>

                        <Card class="mt-4">
                            <LatestBOKRequests
                                :latest_bok_request="latest_bok_request">
                            </LatestBOKRequests>
                        </Card>
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
