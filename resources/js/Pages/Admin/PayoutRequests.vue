<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import IncompletePayoutRequests from './Parts/View/IncompletePayoutRequests.vue';
import CompletePayoutRequests from './Parts/View/CompletePayoutRequests.vue';
import Loader from '@/Components/Loader.vue';

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

</script>

<template>
    <Head title="VeritasApp - wnioski o wypłatę" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <div class="tw-py-12">
            <div class="tw-px-4 tw-mx-auto tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                <div class="tw-flex tw-flex-row tw-gap-6">
                    <div>
                        <button :class="payout_view == 'pending' ? active_button : inactive_button" class="tw-border-solid"
                            @click="payout_view = 'pending'">
                            Oczekujące
                        </button>
                    </div>
                    <div>
                        <button :class="payout_view == 'completed' ? active_button : inactive_button"
                            class="tw-border-solid" @click="payout_view = 'completed'">
                            Zrealizowane
                        </button>
                    </div>
                </div>

                <Transition name="slide-fade" mode="out-in">
                    <div v-if="payout_view == 'pending'">
                        <Suspense>
                            <IncompletePayoutRequests #default :levels="levels">
                            </IncompletePayoutRequests>
                            <template #fallback>
                                <Loader></Loader>
                            </template>
                        </Suspense>
                    </div>
                    <div v-else-if="payout_view == 'completed'">
                        <Suspense>
                            <CompletePayoutRequests #default :levels="levels">
                            </CompletePayoutRequests>
                            <template #fallback>
                                <Loader></Loader>
                            </template>
                        </Suspense>
                    </div>
                </Transition>
            </div>
        </div>
    </AdminLayout>
</template>
