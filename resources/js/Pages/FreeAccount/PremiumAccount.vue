<script setup>

import { Head } from '@inertiajs/vue3';
import UserLayoutNoCRMAccount from '@/Layouts/UserLayoutNoCRMAccount.vue';

</script>

<template>

    <Head title="VeritasApp - zdobądz konto premium" />

    <UserLayoutNoCRMAccount>

        <div class="tw-py-12">
            <div class="!tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <v-card calss="!tw-shadow-2xl !tw-bg-gray-100">
                    <template v-slot:title>
                        <h1 class="tw-text-2xl tw-font-bold">Konto Premium – więcej możliwości dla Ciebie</h1>
                    </template>
                    <v-card-text class="!tw-text-lg">
                        <div>
                            Załóż <span class="tw-text-green-600 tw-font-bold">Konto Premium</span>, aby zyskać dostęp
                            do
                            rozszerzonych funkcji i wyjątkowych korzyści. Dzięki niemu:
                        </div>
                        <div class="tw-mt-4 tw-px-6">
                            <ul class="tw-list-disc">
                                <li>Korzystasz z zaawansowanego systemu wyszukiwania ofert</li>
                                <li>Jesteś w stałym kontakcie ze swoim rekruterem</li>
                                <li>Możesz na bieżąco aktualizować swoją dyspozycyjność</li>
                                <li>Zyskujesz dostęp do <span class="tw-font-bold tw-text-orange-600">programu
                                        lojalnościowego</span>
                                </li>
                                <li>
                                    Masz możliwość <span class="tw-font-bold tw-text-orange-600">polecania opiekunek
                                        oraz rodzin</span> i zdobywania
                                    <span class="tw-font-bold tw-text-orange-600">dodatkowych bonusów</span>
                                </li>
                            </ul>
                        </div>
                        <div class="tw-mt-4">
                            Założenie profilu w naszej bazie danych to pierwszy krok – możesz to zrobić szybko i
                            wygodnie z pomocą naszego
                            konsultanta.
                        </div>
                        <div class="tw-flex tw-flex-row tw-items-center tw-mt-8 tw-gap-4">
                            <i class="fa-solid fa-phone tw-text-2xl"></i>
                            <div class="tw-text-xl">
                                <span class="tw-text-orange-600 tw-font-bold">Zadzwoń: <a href="tel:+48717588140">71 75
                                        88
                                        140</a></span> i dowiedz się,
                                jak
                                <span class="tw-text-green-600 tw-font-bold">Konto Premium</span> może Ci
                                pomóc!
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>

    </UserLayoutNoCRMAccount>/
</template>