<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
</script>

<template>
    <AlertWrapper></AlertWrapper>
    <div class="tw-min-h-screen tw-flex tw-flex-col sm:tw-pt-0 tw-bg-gray-100">
        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-justify-center tw-py-10 tw-px-2 tw-gap-10 lg:tw-gap-0">
            <div class="tw-flex tw-flex-col tw-justify-center lg:tw-items-end tw-items-center lg:tw-px-10">
                <div class="tw-w-full lg:tw-max-w-md tw-px-6 tw-py-4 tw-bg-white tw-shadow-lg tw-shadow-gray-600
                tw-overflow-hidden tw-rounded-lg tw-border-t tw-border-[#fc9003] tw-border-t-8">
                    <div class="tw-flex tw-flex-row tw-justify-center">
                        <Link href="/">
                        <ApplicationLogo class="tw-w-auto tw-h-24 tw-fill-current tw-text-gray-500" />
                        </Link>
                    </div>
                    <slot />
                </div>
            </div>
            <div
                class="lg:tw-flex tw-flex-col tw-justify-center tw-items-start lg:tw-px-10 tw-w-full lg:tw-max-w-3xl tw-w-full">
                <v-card class="!tw-rounded-2xl !tw-border-[#cfe0f5] !tw-border">
                    <v-card-text class="lg:!tw-p-8 !tw-p-4">

                        <div class="tw-text-2xl tw-font-bold tw-text-[#0052a3]">
                            Aplikacja dla opiekunek – zarejestruj się i korzystaj z darmowych możliwości!
                        </div>
                        <div class="tw-text-lg tw-mt-4 tw-text-[#0052a3] tw-font-bold tw-text-xl">
                            Pierwszy krok? Darmowe konto podstawowe
                        </div>
                        <div class="tw-indent-4 tw-text-lg tw-mt-4">
                            Założysz je samodzielnie – wystarczy szybka rejestracja w naszej aplikacji, by natychmiast
                            zyskać dostęp do wielu przydatnych funkcji:
                        </div>
                        <ul class="tw-list-disc tw-pl-6 tw-mt-4 tw-text-lg">
                            <li>przeglądanie aktualnych ofert pracy,</li>
                            <li>możliwość aplikowania na wybrane ogłoszenia,</li>
                            <li>dostęp do najnowszych informacji i postów marketingowych.</li>
                        </ul>
                        <div class="tw-mt-6 tw-text-xl tw-font-bold tw-text-[#0052a3]">
                            Drugi krok? Konto Premium – również darmowe, z jeszcze większymi korzyściami!
                        </div>
                        <div class="tw-indent-4 tw-text-lg tw-mt-4">
                            To rozszerzona wersja konta, którą możesz aktywować razem z naszym konsultantem. Dzięki niej
                            zyskujesz:
                        </div>
                        <ul class="tw-list-disc tw-pl-6 tw-mt-4 tw-text-lg">
                            <li>zaawansowany system wyszukiwania ofert,</li>
                            <li>stały kontakt z osobistym rekruterem,</li>
                            <li>szybki dostęp do aktualizacji swojej dyspozycjności,</li>
                            <li>dostęp do programu lojalnościowego,</li>
                            <li>dodatkowe bonusy za polecanie opiekunek i rodzin.</li>
                        </ul>
                        <div class="tw-flex tw-flex-row tw-gap-4 tw-items-center tw-mt-4 tw-text-lg">
                            <i class="fa-solid fa-phone tw-text-2xl tw-text-red-600"></i>
                            <div>
                                Zadzwoń: <a href="tel:+48717588140"
                                    class="tw-text-[#0052a3] tw-font-bold hover:tw-underline">71 75 88
                                    140</a> - chętnie pomożemy Ci założyć Konto Premium i odpowiemy na wszystkie
                                pytania.
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </div>
</template>
