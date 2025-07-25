<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import IncompletePayoutRequests from './Parts/View/IncompletePayoutRequests.vue';
import CompletePayoutRequests from './Parts/View/CompletePayoutRequests.vue';
import ForApprovalPayoutRequests from './Parts/View/ForApprovalPayoutRequests.vue';
import Loader from '@/Components/Loader.vue';
import Spinner from '@/Components/Forms/Spinner.vue';
import { use_processing_store } from '@/Pinia/ProcessingStore';

const payout_view = ref('pending');
const active_button = ref('tw-text-white tw-bg-blue-600 hover:tw-bg-blue-900 tw-py-3 tw-px-6 tw-rounded-3xl tw-shadow-xl');
const inactive_button = ref('tw-text-blue-600 tw-py-3 tw-shadow-xl tw-px-6 tw-bg-white hover:tw-text-white hover:tw-bg-blue-600 tw-rounded-3xl tw-border tw-border-blue-600');

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
})

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Wnioski o wypłatę',
        disabled: true
    }
]

const tab = ref('one')
const processing_store = use_processing_store()

</script>

<template>

    <Head title="VeritasApp - wnioski o wypłatę" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <v-card rounded="0">
            <Spinner v-if="processing_store.state" />
            <v-tabs v-model="tab" bg-color="#94a3b8" slider-color="#111827" grow>
                <v-tab value="one">W trakcie realizacji</v-tab>
                <v-tab value="two">Do akceptacji</v-tab>
                <v-tab value="three">Zrealizowane</v-tab>
            </v-tabs>

            <v-card-text>
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="one">
                        <v-card title="W trakcie realizacji">
                            <v-card-text class="tw-mt-4">
                                <Suspense>
                                    <IncompletePayoutRequests #default :levels="levels" />
                                    <template #fallback>
                                        <Loader />
                                    </template>
                                </Suspense>
                            </v-card-text>
                        </v-card>
                    </v-tabs-window-item>

                    <v-tabs-window-item value="two">
                        <v-card title="Do akceptacji">
                            <v-card-text class="tw-mt-4">
                                <Suspense>
                                    <ForApprovalPayoutRequests #default :levels="levels" />
                                    <template #fallback>
                                        <Loader />
                                    </template>
                                </Suspense>
                            </v-card-text>
                        </v-card>
                    </v-tabs-window-item>

                    <v-tabs-window-item value="three">
                        <v-card title="Zrealizowane">
                            <v-card-text class="tw-mt-4">
                                <Suspense>
                                    <CompletePayoutRequests #default :levels="levels" />
                                    <template #fallback>
                                        <Loader />
                                    </template>
                                </Suspense>
                            </v-card-text>
                        </v-card>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card-text>
        </v-card>
    </AdminLayout>
</template>
