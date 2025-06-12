<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, router } from '@inertiajs/vue3';
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
    user: {
        type: Object,
        required: true,
    },
    lands: {
        type: Object,
        required: true
    },
    languages: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    },
    waking_up: {
        type: Object,
        required: true
    },
    mobilities: {
        type: Object,
        required: true
    },
    user_offers_id: Array,
    layout: String,
    todays_offers_count: Number
});

console.log('user', props.user)

const user_offers_id = ref(JSON.parse(JSON.stringify(props.user_offers_id)))
console.log('user offers id', user_offers_id.value)

const todays_offers_count = ref(props.todays_offers_count)

const layout_name = props.layout == 'user' ? UserLayout : UserLayoutNoCRMAccount;


const slider = ref(1500)
const processing = ref(false)
const offers = ref([])

const form_init = () => {
    return {
        date: props.filters.date ?? [
            new Date(new Date().setDate(new Date().getDate() + 7)),
            new Date(new Date().setDate(new Date().getDate() + 21))
        ],
        salary: props.filters.salary ?? 1500,
        lands_id: props.filters.lands_id ?? [],
        languages_id: props.filters.languages_id ?? (
            props.user?.user_profiles?.crm_profile?.caretaker_planer_data?.crt_id_language != null
                ? [Number(props.user.user_profiles.crm_profile.caretaker_planer_data.crt_id_language)]
                : []
        ),
        waking_up_id: props.filters?.waking_up_id ?? (
            props.user?.user_profiles?.crm_profile?.caretaker_planer_data?.crt_waking_up != null
                ? [Number(props.user.user_profiles.crm_profile.caretaker_planer_data.crt_waking_up)]
                : []
        ),
        mobility_id: props.filters?.mobility_id ?? []
    }
}
const form = ref(form_init())
console.log('waking_up', props.user?.user_profiles?.crm_profile?.caretaker_planer_data?.crt_waking_up)

const show_sidebar = ref(false)

onBeforeMount(async () => {
    console.log(form.value)
    await submit()
})

const submit = async () => {
    processing.value = true

    console.log(form.value)

    try {
        let response = await axios.post(route('offer.download.no.crm'), form.value)
        console.log('response', response)
        response = response.data

        offers.value = response.offers
        console.log(offers.value)

    } catch (error) {
        console.log('offer search error: ', error)
        processing.value = false
    }

    processing.value = false
}

const currentPage = ref(1)
const itemsPerPage = 20
const bottomObserver = ref(null)

const visibleOffers = computed(() => {
    const start = 0
    const end = currentPage.value * itemsPerPage
    return offers.value.slice(start, end)
})

onMounted(async () => {
    // Intersection Observer do scrollowania
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            loadMore()
        }
    }, {
        root: null,
        threshold: 1.0,
    })

    if (bottomObserver.value) {
        observer.observe(bottomObserver.value)
    } else {
        console.warn('bottomObserver not found')
    }
})

const loadMore = () => {
    const totalPages = Math.ceil(offers.value.length / itemsPerPage)
    if (currentPage.value < totalPages) {
        currentPage.value++
    }
}

// Nasłuchiwanie na pojawienie się observera w widoku
useIntersectionObserver(
    bottomObserver,
    ([{ isIntersecting }]) => {
        if (isIntersecting) {
            loadMore()
        }
    },
    {
        threshold: 1.0,
    }
)

const offer_processing = ref(false)

const offer_store = async (offer, index) => {

    offer_processing.value = true
    // btns.value[index]['disabled'] = true
    // btns.value[index]['text'] = 'Zgłaszam...'
    let response = await axios.post(route('offer.store'), {
        ...offer
    })
    response = response.data
    console.log(response)

    if (response.success) {
        // emit('update:submitted', index)
        alert(response.msg)
        user_offers_id.value.push(response.offer_id)
        console.log(user_offers_id.value)

        todays_offers_count.value++

    } else {

        // btns.value[index]['disabled'] = false
        // btns.value[index]['text'] = 'Zgłoś się'

        // emit('update-btns', btns.value)
    }

    offer_processing.value = false
}

</script>

<template>

    <Head title="VeritasApp - strona główna" />

    <UserLayout>
        <div @click="router.get(route('offer.my_offers_free_account'))"
            class="hover:tw-cursor-pointer tw-z-[9999] tw-bg-gray-100 tw-fixed tw-bottom-4 tw-left-2 tw-text-xl tw-border tw-border-indigo-500 tw-text-indigo-500 tw-px-4 tw-py-1 tw-rounded-xl tw-text-center">
            Dostępnych zgłoszeń:<br>
            <span class="tw-text-2xl tw-font-bold tw-text-red-700">
                {{ 3 -
                    todays_offers_count }}
            </span>
        </div>
        <div class="tw-fixed tw-block sm:tw-hidden tw-bottom-24 tw-left-2 tw-z-[99] tw-bg-indigo-600 tw-rounded-[50%] tw-p-3 tw-border tw-border-gray-200 hover:tw-cursor-pointer"
            @click="show_sidebar = true">
            <i class="fa-solid fa-filters tw-text-3xl tw-text-gray-200"></i>
        </div>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <h1 class="tw-font-bold tw-text-2xl">Oferty pracy</h1>
                <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-4 tw-mt-4">
                    <Transition name="slide">
                        <div v-if="show_sidebar"
                            class="tw-w-full lg:tw-w-1/3 tw-fixed tw-top-0 tw-left-0 tw-h-[900px] tw-z-[99] tw-block sm:tw-hidden">
                            <v-card class="!tw-overflow-visible tw-fixed tw-top-0 tw-left-0 tw-h-screen !tw-shadow-xl">
                                <template v-slot:subtitle>
                                    <div class="tw-text-right">
                                        <i class="fa-regular fa-circle-xmark tw-text-3xl hover:tw-cursor-pointer"
                                            @click="show_sidebar = false"></i>
                                    </div>
                                </template>
                                <v-card-text>
                                    <div class="tw-flex tw-flex-col tw-gap-6">
                                        <div>
                                            <label for="" class="tw-text-base">Data wyjazdu</label>
                                            <VueDatePicker class="tw-mt-2" v-model="form.date" range
                                                placeholder="Zakres daty wyjazdu" auto-apply :format="format_range" />
                                        </div>
                                        <v-select label="Poziom języka" multiple clearable chips variant="outlined"
                                            density="compact" :items="languages" item-title="pll_language_exp"
                                            item-value="pll_id" v-model="form.languages_id" />
                                        <v-select label="Land" multiple clearable chips variant="outlined"
                                            density="compact" :items="lands" item-title="name_pl" item-value="id"
                                            v-model="form.lands_id" />
                                        <v-slider label="Stawka" :min="1500" :max="2500" v-model="form.salary"
                                            thumb-label="always" step="50" class="tw-mt-4"></v-slider>
                                    </div>
                                </v-card-text>
                                <v-card-actions>
                                    <div class="tw-flex tw-flex-row">
                                        <v-btn variant="tonal" color="#2563eb">Szukaj</v-btn>
                                        <v-btn variant="tonal" color="#dc2626">Usuń filtry</v-btn>
                                    </div>
                                </v-card-actions>
                            </v-card>
                        </div>
                    </Transition>
                    <div class="tw-w-full lg:tw-w-1/3 tw-hidden sm:tw-block">
                        <v-card class="!tw-overflow-visible">
                            <v-card-text>
                                <div class="tw-flex tw-flex-col tw-gap-6">
                                    <div>
                                        <label for="" class="tw-text-base">Data wyjazdu</label>
                                        <VueDatePicker class="tw-mt-2" v-model="form.date" range
                                            placeholder="Zakres daty wyjazdu" auto-apply :format="format_range" />
                                    </div>
                                    <v-select label="Poziom języka" multiple clearable chips variant="outlined"
                                        density="compact" :items="languages" item-title="pll_language_exp"
                                        item-value="pll_id" v-model="form.languages_id" />
                                    <v-select label="Land" multiple clearable chips variant="outlined" density="compact"
                                        :items="lands" item-title="name_pl" item-value="id" v-model="form.lands_id" />
                                    <v-slider label="Stawka" :min="1500" :max="2500" v-model="form.salary"
                                        thumb-label="always" step="50" class="tw-mt-4" />
                                    <v-select label="Wstawanie w nocy" multiple clearable chips variant="outlined"
                                        density="compact" :items="waking_up" item-title="fwu_waking_up_type"
                                        item-value="fwu_id" v-model="form.waking_up_id" />
                                    <v-select label="Mobilność" multiple clearable chips variant="outlined"
                                        density="compact" :items="mobilities" item-title="plm_mobility_type"
                                        item-value="plm_id" v-model="form.waking_up_id" />
                                </div>
                            </v-card-text>
                            <v-card-actions>
                                <div class="tw-flex tw-flex-row">
                                    <v-btn variant="tonal" color="#2563eb" @click="submit()">Szukaj</v-btn>
                                    <v-btn variant="tonal" color="#dc2626" @click="form = form_init()">Usuń
                                        filtry</v-btn>
                                </div>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <Transition mode="out-in">
                        <div v-if="processing" class="tw-w-full">
                            <v-alert class="!tw-p-8" color="#f97316">
                                <template v-slot:prepend>
                                    <i class="fa-solid fa-magnifying-glass tw-text-3xl tw-animate-bounce"></i>
                                </template>
                                <div class="tw-text-xl tw-ms-4">
                                    Szukam ofert...
                                </div>
                            </v-alert>
                        </div>
                        <div v-else class="tw-w-full tw-flex tw-flex-col tw-gap-4">
                            <Transition mode="out-in">
                                <div v-if="offers.length == 0">
                                    <v-alert type="info">Brak ofert spełniających podane
                                        kryteria.</v-alert>
                                </div>

                                <div v-else class="tw-flex tw-flex-col tw-gap-2">
                                    <TransitionGroup name="list" tag="div"
                                        class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-4">
                                        <div v-for="(item, index) in visibleOffers" :key="index">
                                            <v-card class="!tw-shadow-xl !tw-p-3 !tw-h-full">
                                                <template v-slot:title>
                                                    <div class="tw-flex tw-flex-row tw-items-center tw-gap-3">
                                                        <div
                                                            class="tw-bg-orange-100 tw-w-[40px] tw-h-[40px] tw-rounded tw-flex tw-justify-center tw-items-center">
                                                            <div class="tw-text-orange-500 tw-text-center">
                                                                #
                                                            </div>
                                                        </div>
                                                        <div class="tw-text-black tw-font-bold">{{ item.pnr_id_planer }}
                                                        </div>
                                                    </div>
                                                </template>
                                                <template v-slot:subtitle>
                                                    <div class="tw-text-gray-400 tw-mt-1">Oferta opieki</div>
                                                    <!-- <div class="tw-text-gray-900 !tw-opacity-100 tw-font-bold">
                                                        Stawka NETTO <span class="tw-text-blue-700">{{
                                                            item.pnr_caretaker_rate
                                                            }}
                                                            €</span>
                                                    </div> -->
                                                </template>
                                                <v-card-text class="tw-text-gray-900">
                                                    <!-- {{ item }} -->
                                                    <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                                        <div
                                                            class="tw-bg-gray-100 tw-flex tw-flex-col tw-rounded-lg tw-p-3">
                                                            <div class="tw-text-gray-500">Stawka NETTO</div>
                                                            <div class="tw-font-bold tw-text-lg">
                                                                {{ item.pnr_caretaker_rate }} €
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="tw-bg-gray-100 tw-flex tw-flex-col tw-rounded-lg tw-p-3">
                                                            <div class="tw-text-gray-500">Data przyjazdu</div>
                                                            <div class="tw-font-bold tw-text-lg">
                                                                {{ item.pnr_start_date }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tw-my-6">
                                                        <div
                                                            class="tw-flex tw-flex-row tw-items-center tw-gap-2 tw-text-lg">
                                                            <i class="fa-solid fa-user tw-text-lg"></i>
                                                            <span>
                                                                {{ item.family.patient.length }} {{
                                                                    item.family.patient.length == 1 ?
                                                                        'pacjent' : 'pacjentów' }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="tw-flex tw-flex-row tw-items-center tw-gap-2 tw-text-lg">
                                                            <i class="fa-solid fa-language tw-text-lg"></i>
                                                            <div>
                                                                Poziom języka -
                                                                <span class="tw-font-bold tw-text-orange-500">
                                                                    {{languages.filter(i => i.pll_id ==
                                                                        item.pnr_id_rate_language)[0].pll_language_exp}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tw-grid"
                                                        :class="item.family.patient.length == 1 ? 'tw-grid-cols-1' : 'tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-2'">
                                                        <div
                                                            class="tw-bg-gray-100 tw-flex tw-flex-col tw-mt-2 tw-rounded-lg tw-p-3">
                                                            <div class="tw-text-gray-500">Pacjent #1</div>
                                                            <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                                                                <div
                                                                    class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                                    <i class="fa-solid fa-house-night"></i>
                                                                    <div>
                                                                        {{
                                                                            item.family.patient[0]?.waking_up?.fwu_waking_up_type
                                                                            ?? 'brak'
                                                                        }}
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                                    <i class="fa-solid fa-person-walking-with-cane"></i>
                                                                    <div>
                                                                        {{
                                                                            item.family.patient[0]?.mobility?.plm_mobility_type
                                                                            ?? 'brak'
                                                                        }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div v-if="item.family.patient.length == 2"
                                                            class="tw-bg-gray-100 tw-flex tw-flex-col tw-mt-2 tw-rounded-lg tw-p-3">
                                                            <div class="tw-text-gray-500">Pacjent #2</div>
                                                            <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                                                                <div
                                                                    class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                                    <i class="fa-solid fa-house-night"></i>
                                                                    <div>
                                                                        {{
                                                                            item.family.patient[1]?.waking_up?.fwu_waking_up_type
                                                                            ?? 'brak'
                                                                        }}
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                                                                    <i class="fa-solid fa-person-walking-with-cane"></i>
                                                                    <div>
                                                                        {{
                                                                            item.family.patient[1]?.mobility?.plm_mobility_type
                                                                            ?? 'brak'
                                                                        }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="tw-bg-gray-100 tw-flex tw-flex-col tw-mt-2 tw-rounded-lg tw-p-3">
                                                        <div class="tw-text-gray-500">Miejsowość</div>
                                                        <div class="tw-font-bold tw-text-lg">
                                                            {{ item.family.address.adr_city }}, Niemcy
                                                        </div>
                                                    </div>
                                                    <!-- <div class="tw-flex tw-flex-col">
                                                        <div>Język - {{languages.filter(i => i.pll_id ==
                                                            item.pnr_id_rate_language)[0].pll_language_exp}}</div>
                                                        <div>Data przyjazdu - {{ item.pnr_start_date }}</div>
                                                        <div>Data zjazdu - brak</div>
                                                        <div>Miejscowość - <span class="tw-text-orange-500">{{
                                                            item.family.address.adr_city
                                                                }}</span></div>
                                                    </div>
                                                    <div class="tw-mt-4 tw-text-lg">
                                                        Ilość pacjentów - <span class="tw-text-orange-500">{{
                                                            item.family.patient.length }}</span>
                                                    </div> -->
                                                    <!-- <div class="tw-grid tw-grid-cols-2 tw-mt-3">
                                                        <div class="tw-flex tw-flex-col">
                                                            <div>
                                                                Pacjent #1
                                                            </div>
                                                            <div class="tw-flex tw-flex-row tw-mt-2">
                                                                Mobilność - {{
                                                                    item.family.patient[0]?.mobility?.plm_mobility_type
                                                                    ?? 'brak'
                                                                }}
                                                            </div>
                                                            <div class="tw-flex tw-flex-row">
                                                                Wstawanie w nocy - {{
                                                                    item.family.patient[0]?.waking_up?.fwu_waking_up_type
                                                                    ?? 'brak'
                                                                }}
                                                            </div>
                                                        </div>
                                                        <div class="tw-flex tw-flex-col"
                                                            v-if="item.family.patient.length == 2">
                                                            <div>
                                                                Pacjent #2
                                                            </div>
                                                            <div class="tw-flex tw-flex-row tw-mt-2">
                                                                Mobilność - {{
                                                                    item.family.patient[1]?.mobility?.plm_mobility_type
                                                                    ?? 'brak'
                                                                }}
                                                            </div>
                                                            <div class="tw-flex tw-flex-row">
                                                                Wstawanie w nocy - {{
                                                                    item.family.patient[1]?.waking_up?.fwu_waking_up_type
                                                                    ?? 'brak'
                                                                }}
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </v-card-text>
                                                <v-card-actions>
                                                    <div class="tw-w-full tw-flex tw-flex-row"
                                                        v-if="todays_offers_count < 3">
                                                        <Transition mode="out-in" name="fade">
                                                            <div v-if="user_offers_id.includes(item.pnr_id_planer)"
                                                                class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-text-green-600 tw-text-xl">
                                                                <i class="fa-regular fa-thumbs-up tw-text-2xl"></i>
                                                                <span>Zgłoszono na ofertę</span>
                                                            </div>
                                                            <v-btn v-else variant="flat" color="#16a34a"
                                                                class="tw-w-full !tw-rounded-lg"
                                                                :disabled="offer_processing"
                                                                @click="offer_store(item, index)">{{
                                                                    offer_processing ?
                                                                        'Zgłaszam na ofertę' :
                                                                        '+ Zgłoś się na ofertę' }}
                                                            </v-btn>
                                                        </Transition>
                                                    </div>
                                                </v-card-actions>
                                            </v-card>
                                        </div>
                                    </TransitionGroup>
                                </div>
                            </Transition>
                        </div>
                    </Transition>
                </div>
            </div>
            <div ref="bottomObserver" style="height: 1px; margin-bottom: 20px;" />
        </div>
    </UserLayout>
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
