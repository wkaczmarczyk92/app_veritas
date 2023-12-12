<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import IncompletePayoutRequests from './Parts/View/IncompletePayoutRequests.vue';
import CompletePayoutRequests from './Parts/View/CompletePayoutRequests.vue';
import Loader from '@/Components/Loader.vue';

const payout_view = ref('pending');
const active_button = ref('text-white bg-blue-600 hover:bg-blue-900 py-3 px-6 rounded-3xl shadow-xl');
const inactive_button = ref('text-blue-600 py-3 shadow-xl px-6 bg-white hover:text-white hover:bg-blue-600 rounded-3xl border border-blue-600');

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
})


</script>

<template>
    <Head title="VeritasApp - wnioski o wypłatę" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Wnioski o wypłatę</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 px-4 lg:px-8">
                <div class="flex flex-row gap-6">
                    <div>
                        <button 
                            :class="payout_view == 'pending' ? active_button : inactive_button"
                            @click="payout_view = 'pending'"
                            >
                            Oczekujące
                        </button>
                    </div>
                    <div>
                        <button
                            :class="payout_view == 'completed' ? active_button : inactive_button"
                            @click="payout_view = 'completed'"
                            >
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
