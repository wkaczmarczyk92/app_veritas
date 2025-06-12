<script setup>

import { ref, toRef, onBeforeMount } from 'vue';
import { contract_recive_date, has_business } from './Composables/DisplayConditions';



import Button from '@/Composables/Buttons/Button.vue';

const props = defineProps({
    caretaker: Object,
})

console.log(props.caretaker.assignments[0])

const _false = '#6b7280'
const _true = '#16a34a'

const _true_icon = 'fa-regular fa-circle-check'
const _false_icon = 'fa-regular fa-circle-xmark'


var contract_items = []

const create_contract = (caretaker) => {
    let items = []

    if (!caretaker || !caretaker.assignments[0] || !caretaker.assignments[0].contract) {
        return items
    }


    let contract = caretaker.assignments[0] && caretaker.assignments[0].contract ? caretaker.assignments[0].contract : null

    items.push({
        id: 1,
        text: 'Wysyłka - ' + (contract.con_id_contract_to ? contract.contract_to.cnt_contract_to_name : 'brak'),
        icon: contract.con_id_contract_to ? _true_icon : _false_icon,
        color: contract.con_id_contract_to ? _true : _false,
    })

    items.push({
        id: 2,
        text: 'Data wysyłki - ' + (contract.con_send_date ?? 'brak'),
        icon: contract.con_send_date ? _true_icon : _false_icon,
        color: contract.con_send_date ? _true : _false,
    })

    items.push({
        id: 3,
        text: `Data odebrania - ${contract_recive_date(caretaker)}`,
        icon: contract_recive_date(caretaker) != 'brak' ? _true_icon : _false_icon,
        color: contract_recive_date(caretaker) != 'brak' ? _true : _false,
    })

    if (contract.con_resend_date != null) {
        items.push({
            id: 4,
            text: 'Data ponownej wysyłki - ' + contract.con_resend_date,
            icon: _true_icon,
            color: _true
        })
    }

    console.log(items)
    contract_items = items
}

const receive_date = (caretaker) => {
    if (caretaker.assignments[0].contract.con_receive_date == null || caretaker.assignments[0].contract.a1 == null || caretaker.assignments[0].contract.a1.one_submission_date == null) {
        return 'brak'
    } else {
        return caretaker.assignments[0].contract.con_receive_date
    }

}

const contract = create_contract(props.caretaker)

const is_dpd = () => {
    return (props.caretaker.assignments[0].contract && props.caretaker.assignments[0].contract.dpd && props.caretaker.assignments[0].contract.dpd.cpd_waybill)

}

</script>

<template>
    <v-window-item value="one">
        <!-- {{ props.caretaker.assignments[0].contract }} -->
        <div v-if="!caretaker.assignments[0].contract">
            <v-alert color="info" icon="$info" class="!tw-py-8"
                :text="`Brak informacji o umowie. Prosimy sprawdzić za jakiś czas.`" />
        </div>
        <div v-else class="tw-flex tw-flex-col">

            <div class="tw-flex tw-flex-row">
                <v-timeline side="end">
                    <v-timeline-item v-for="item in contract_items" :key="item.id" :dot-color="item.color" size="small"
                        class="tw-relative">
                        <template v-slot:default>
                            <div v-html="item.text"></div>
                        </template>
                        <template v-slot:icon>
                            <i
                                :class="`${item.icon} tw-text-white tw-absolute tw-left-[50%] tw-top-[50%] -tw-translate-y-[50%] -tw-translate-x-[50%]`"></i>
                        </template>
                    </v-timeline-item>
                </v-timeline>
            </div>
            <div class="tw-px-6 tw-w-full tw-my-8" v-if="is_dpd()">
                <a
                    :href="`https://tracktrace.dpd.com.pl/parcelDetails?typ=1&p1=${caretaker.assignments[0].contract.dpd.cpd_waybill}`">
                    <Button value="Śledź swoją przesyłkę" color="rose" variant="tonal">
                        <template v-slot:icon>
                            <i class="fa-regular fa-truck tw-text-xl"></i>
                        </template>
                    </Button>
                </a>
            </div>
            <div class="tw-text-green-600 tw-flex tw-flex-row tw-gap-2 tw-items-center tw-px-8 tw-mt-6 tw-mb-3"
                v-if="!has_business(caretaker)">
                <p>Jesteś zarejestrowany/a</p>
                <i class="fa-solid fa-check"></i>
            </div>
        </div>
    </v-window-item>
</template>
