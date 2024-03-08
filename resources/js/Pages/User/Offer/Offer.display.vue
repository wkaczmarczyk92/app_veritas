<template>
    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-4 tw-mt-8 tw-justify-items-stretch"
        v-if="Object.keys(offers).length > 0">
        <div v-for="(offer, index) in offers" :key="index">
            <div
                class="tw-border-2 tw-border-gray-600 tw-rounded-xl tw-text-white tw-p-4 tw-bg-gray-800 tw-shadow-2xl tw-h-full">
                <div class="offer__box tw-relative tw-h-full tw-pb-20">
                    <div class="offer__title tw-text-center tw-text-lg">
                        Oferta <span class="tw-text-orange-500 tw-font-bold">#{{
                            offer.pnr_id_planer
                        }}</span>
                    </div>
                    <div class="tw-flex tw-flex-row tw-justify-center">
                        <hr class="tw-my-4 tw-w-1/2 tw-border-blue-500">
                    </div>
                    <div class="offer__body mt-2 tw-text-sm">
                        <div class="tw-flex tw-flex-row tw-justify-between">
                            <div>
                                Stawka <span class="text-blue-600 font-bold">NETTO</span>
                            </div>
                            <div>
                                <span class="tw-text-orange-500 tw-font-bold">{{
                                    offer.pnr_caretaker_rate
                                }}€</span>
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-row tw-justify-between">
                            <div>
                                Data przyjazdu
                            </div>
                            <div>
                                {{ offer.pnr_start_date }}
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-row tw-justify-between">
                            <div>
                                Data zjazdu
                            </div>
                            <div>
                                {{ offer.pnr_end_date ?? 'brak' }}
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-row tw-justify-between tw-mt-4">
                            <div>
                                Ilość pacjentów
                            </div>
                            <div>
                                <span class="tw-text-orange-500 tw-font-bold">{{
                                    offer.family.patient.length
                                    ?? 'brak' }}</span>
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-row tw-justify-between tw-mt-2">
                            <div>
                                Pacjenci:
                            </div>
                        </div>
                        <div class="" v-for="(patient, index) in offer.family.patient">
                            <div class="tw-flex tw-flex-row tw-justify-between">
                                <div class="tw-mt-1 tw-ml-2">
                                    #{{ index + 1 }}
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-justify-between tw-text-xs">
                                <div class="tw-ml-2">
                                    Mobilność
                                </div>
                                <div>
                                    {{ patient && patient.mobility ? patient.mobility.plm_mobility_type : '-' }}
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-justify-between tw-text-xs">
                                <div class="tw-ml-2">
                                    Wstawanie w nocy
                                </div>
                                <div>
                                    {{ patient?.waking_up?.fwu_waking_up_type ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="tw-flex tw-flex-row tw-justify-between mt-4">
                            <div>
                                Miejscowość
                            </div>
                            <div>
                                <span class="tw-text-orange-500 tw-font-bold">{{
                                    offer.family.address.adr_city
                                    ?? 'brak' }}</span>
                            </div>
                        </div>


                    </div>
                    <div
                        class="tw-flex tw-flex-row tw-justify-center tw-absolute tw-bottom-0 tw-left-1/2 -tw-translate-x-1/2 tw-w-full">
                        <Transition name="fade" mode="out-in">
                            <MButton v-if="!submitted.includes(index)"
                                :add_class="btns[index].disabled ? 'disabled:tw-opacity-50 hover:tw-bg-green-700 tw-mt-6' : 'tw-mt-6'"
                                :value="btns[index].text" @click="offer_store(offer, index)"
                                :disabled="btns[index].disabled"></MButton>
                            <span v-else class="tw-mt-5 tw-text-green-600 tw-text-lg">Dziekujemy za
                                zgłoszenie!</span>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <OfferNotFound v-else />
    <div class="tw-flex tw-flex-col tw-justify-center tw-mt-8 tw-gap-2 tw-items-center">

        <div class="tw-flex tw-flex-row tw-justify-center tw-items-center tw-gap-2">
            <MButton v-if="Object.keys(offers).length > 0" value="Pokaż inne oferty" icon="fa-solid fa-repeat"
                bg="tw-bg-orange-500" hover="hover:tw-bg-orange-700" add_class="tw-text-2xl tw-px-8 tw-py-4"
                :disabled="false" @click="$emit('search')"></MButton>
            <MButton value="Wybierz inne landy" icon="fa-regular fa-globe-pointer" bg="tw-bg-violet-500"
                hover="hover:tw-bg-violet-700" add_class="tw-text-2xl tw-px-8 tw-py-4" @click="$emit('clear-search')">
            </MButton>
        </div>
    </div>
</template>

<script setup>

import MButton from '@/Components/Buttons/MButton.vue';

import { toRef } from 'vue';

const props = defineProps({
    submitted: {
        type: Array,
        required: true
    },
    offers: {
        type: Object,
        required: true
    },
    btns: {
        type: Object,
        required: true
    }
})

const btns = toRef(props.btns)

const emit = defineEmits([
    'update:submitted',
    'search',
    'clear-search',
    'update-btns'
])



const offer_store = async (offer, index) => {
    btns.value[index]['disabled'] = true
    btns.value[index]['text'] = 'Zgłaszam...'
    let response = await axios.post(route('offer.store'), {
        ...offer
    })
    response = response.data
    console.log(response)

    if (response.success) {
        emit('update:submitted', index)

    } else {

        btns.value[index]['disabled'] = false
        btns.value[index]['text'] = 'Zgłoś się'

        emit('update-btns', btns.value)
    }

}

</script>
