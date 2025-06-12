<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, toRef, onBeforeMount, watch, computed, onMounted } from 'vue';

import { useUserStore } from '@/Pinia/UserStore'

import Post from '../Homepage/Parts/Post.vue';
import ImportantInfo from '../Homepage/Parts/ImportantInfo.vue';
// import FilesToDownload from './Parts/FilesToDownload.vue';
import OnlineCourses from '../Homepage/Parts/OnlineCourses.vue';
import PayoutRequest from '../Homepage/Parts/PayoutRequest.vue';
import FamilyRecommendation from '../Homepage/Parts/FamilyRecommendation.vue';
import CaretakerRecommendation from '../Homepage/Parts/CaretakerRecommendation.vue';
import MyPersonalData from '../Homepage/Parts/MyPersonalData.vue';
import UserLogoCard from '../Homepage/Parts/UserLogoCard.vue';
import UserLayoutNoCRMAccount from '@/Layouts/UserLayoutNoCRMAccount.vue';
// import Offers from './Parts/Offers.vue';
import { format, format_range } from '@/Components/Functions/DateFormat.vue';
import { useIntersectionObserver } from '@vueuse/core'

const props = defineProps({
    offers: {
        type: [Object, null],
        required: true
    },
    filters: {
        type: Object,
        required: true
    },
    lands: {
        type: Object,
        required: true
    },
    languages: {
        type: Object,
        required: true
    },
    layout: String
})

console.log('języki - ', props.languages)
const form_init = () => {
    return {
        date: props.filters.date ?? [
            new Date(new Date().setDate(new Date().getDate() + 7)),
            new Date(new Date().setDate(new Date().getDate() + 21))
        ],
        salary: props.filters.salary ?? 1500,
        lands_id: props.filters.lands_id ?? [],
        languages_id: props.filters.languages_id ?? []
    }
}
const form = ref(form_init())
const layout_name = ref(props.layout == 'user' ? UserLayout : UserLayoutNoCRMAccount)

const show_sidebar = ref(false)

</script>

<template>

    <Head title="VeritasApp - moje zgłoszenia na oferty pracy" />

    <component :is="layout_name">
        <div class="tw-fixed tw-block sm:tw-hidden tw-bottom-2 tw-left-2 tw-z-[99] tw-bg-indigo-600 tw-rounded-[50%] tw-p-3 tw-border tw-border-gray-200 hover:tw-cursor-pointer"
            @click="show_sidebar = true">
            <i class="fa-solid fa-filters tw-text-3xl tw-text-gray-200"></i>
        </div>
        <div class="tw-py-12">

            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <h1 class="tw-font-bold tw-text-2xl">Moje zgłoszenia na oferty</h1>
                <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-4 tw-mt-4">

                    <v-alert v-if="offers.length == 0" color="info">Brak zgłoszeń</v-alert>
                    <div class="tw-w-full tw-flex tw-flex-col tw-gap-4" v-else>
                        <TransitionGroup name="list" tag="div"
                            class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-4">
                            <div v-for="(item, index) in offers" :key="index">
                                <v-card class="!tw-shadow-xl !tw-p-3 !tw-h-full">
                                    <template v-slot:title>
                                        <div class="tw-flex tw-flex-row tw-items-center tw-gap-3">
                                            <div
                                                class="tw-bg-orange-100 tw-w-[40px] tw-h-[40px] tw-rounded tw-flex tw-justify-center tw-items-center">
                                                <div class="tw-text-orange-500 tw-text-center">
                                                    #
                                                </div>
                                            </div>
                                            <div class="tw-text-black tw-font-bold">{{ item.planer.pnr_id_planer }}
                                            </div>
                                        </div>
                                    </template>
                                    <template v-slot:subtitle>
                                        <div class="tw-text-gray-400 tw-mt-1">Oferta opieki</div>
                                    </template>
                                    <v-card-text class="tw-text-gray-900">
                                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                            <div class="tw-bg-gray-100 tw-flex tw-flex-col tw-rounded-lg tw-p-3">
                                                <div class="tw-text-gray-500">Stawka NETTO</div>
                                                <div class="tw-font-bold tw-text-lg">
                                                    {{ item.planer.pnr_caretaker_rate }} €
                                                </div>
                                            </div>
                                            <div class="tw-bg-gray-100 tw-flex tw-flex-col tw-rounded-lg tw-p-3">
                                                <div class="tw-text-gray-500">Data przyjazdu</div>
                                                <div class="tw-font-bold tw-text-lg">
                                                    {{ item.planer.pnr_start_date }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tw-my-6">
                                            <div class="tw-flex tw-flex-row tw-items-center tw-gap-2 tw-text-lg">
                                                <i class="fa-solid fa-user tw-text-lg"></i>
                                                <span>
                                                    {{ item.planer.family.patient.length }} {{
                                                        item.planer.family.patient.length == 1 ?
                                                            'pacjent' : 'pacjentów' }}
                                                </span>
                                            </div>
                                            <div class="tw-flex tw-flex-row tw-items-center tw-gap-2 tw-text-lg">
                                                <i class="fa-solid fa-language tw-text-lg"></i>
                                                <div>
                                                    Poziom języka -
                                                    <span class="tw-font-bold tw-text-orange-500">
                                                        {{languages.filter(i => i.pll_id ==
                                                            item.planer.pnr_id_rate_language)[0].pll_language_exp}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tw-grid"
                                            :class="item.planer.family.patient.length == 1 ? 'tw-grid-cols-1' : 'tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-2'">
                                            <div
                                                class="tw-bg-gray-100 tw-flex tw-flex-col tw-mt-2 tw-rounded-lg tw-p-3">
                                                <div class="tw-text-gray-500">Pacjent #1</div>
                                                <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                                                    <div class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                        <i class="fa-solid fa-house-night"></i>
                                                        <div>
                                                            {{
                                                                item.planer.family.patient[0]?.waking_up?.fwu_waking_up_type
                                                                ?? 'brak'
                                                            }}
                                                        </div>
                                                    </div>
                                                    <div class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                        <i class="fa-solid fa-person-walking-with-cane"></i>
                                                        <div>
                                                            {{
                                                                item.planer.family.patient[0]?.mobility?.plm_mobility_type
                                                                ?? 'brak'
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="item.planer.family.patient.length == 2"
                                                class="tw-bg-gray-100 tw-flex tw-flex-col tw-mt-2 tw-rounded-lg tw-p-3">
                                                <div class="tw-text-gray-500">Pacjent #2</div>
                                                <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                                                    <div class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                        <i class="fa-solid fa-house-night"></i>
                                                        <div>
                                                            {{
                                                                item.planer.family.patient[1]?.waking_up?.fwu_waking_up_type
                                                                ?? 'brak'
                                                            }}
                                                        </div>
                                                    </div>
                                                    <div class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                        <i class="fa-solid fa-person-walking-with-cane"></i>
                                                        <div>
                                                            {{
                                                                item.planer.family.patient[1]?.mobility?.plm_mobility_type
                                                                ?? 'brak'
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tw-bg-gray-100 tw-flex tw-flex-col tw-mt-2 tw-rounded-lg tw-p-3">
                                            <div class="tw-text-gray-500">Miejsowość</div>
                                            <div class="tw-font-bold tw-text-lg">
                                                {{ item.planer.family.address.adr_city }}, Niemcy
                                            </div>
                                        </div>
                                    </v-card-text>
                                    <v-card-actions>
                                        <div class="tw-w-full tw-flex tw-flex-row">
                                            <div class="tw-w-full tw-flex tw-flex-row tw-justify-end">
                                                Zgłoszono na ofertę - {{ format(item.created_at) }}
                                            </div>
                                        </div>
                                    </v-card-actions>
                                </v-card>
                            </div>
                        </TransitionGroup>
                    </div>
                </div>

            </div>
        </div>
    </component>
</template>
<style>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.5s ease-in-out, opacity 0.5s;
}

.slide-enter-from {
    transform: translateX(-100%);
    opacity: 0;
}

.slide-enter-to {
    transform: translateX(0);
    opacity: 1;
}

.slide-leave-from {
    transform: translateX(0);
    opacity: 1;
}

.slide-leave-to {
    transform: translateX(-100%);
    opacity: 0;
}

.list-move,
/* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
    position: absolute;
}

.v-card-subtitle {
    opacity: 1 !important;
}
</style>
