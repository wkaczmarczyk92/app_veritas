<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, defineComponent } from 'vue';
import NewPagination from '@/Components/Navigation/NewPagination.vue';

import { Responsive, Chart, Line, Grid, Tooltip } from 'vue3-charts'

const props = defineProps({
    last_logins: {
        type: Object,
        default: {}
    }
});

console.log('last logins', props.last_logins);
const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Ostatnie logowania',
        disabled: true,
    }
]

const axis = ref({
    primary: {
        type: 'band',
        label: {
            rotation: -45,        // obrót o -45 stopni
            textAnchor: 'end'     // wyrównanie do końca (aby etykiety nie były obcięte)
        }
    },
    secondary: {
        domain: ['dataMin', 'dataMax + 100'],
        type: 'linear',
        ticks: 8
    }
})


const data_init = () => {
    let data = []

    props.last_logins.forEach((item, index) => {
        data.push({
            name: item.date,
            login_count: item.login_count
        })
    })

    return data
}

const data = ref(data_init().reverse())


const direction = ref('horizontal')
const margin = ref({
    left: 0,
    top: 20,
    right: 20,
    bottom: 50
})



// export default defineComponent({
//     name: 'LineChart',
//     components: { Chart, Grid, Line },
//     setup() {


//         return { data, direction, margin, axis }
//     }
// })

</script>

<template>

    <Head :title="`VeritasApp - statystyka logowań`" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-px-1">
            <v-card title="Statystyka logowań - ostatnie 30 dni">
                <v-card-text>
                    <div class="tw-overflow-x-auto tw-w-full">
                        <Responsive class="w-full">
                            <template #main="{ width }">
                                <Chart :size="{ width, height: 500 }" :data="data" :margin="margin" :axis="axis"
                                    :direction="direction">
                                    <template #layers>
                                        <Grid strokeDasharray="2,2" />
                                        <Line :dataKeys="['name', 'login_count']" />
                                    </template>
                                    <template #widgets>
                                        <Tooltip borderColor="#48CAE4" :config="{
                                            name: { hide: true },
                                            login_count: { label: 'ilość logowań', color: '#0077b6' },
                                        }" />
                                    </template>
                                </Chart>
                            </template>
                        </Responsive>
                    </div>
                </v-card-text>
            </v-card>
            <!-- <div class="tw-px-4 sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-px-6 tw-pt-16 tw-pb-8 tw-bg-gray-100 tw-rounded tw-shadow-xl sm:tw-rounded-lg sm:tw-px-20 sm:tw-pb-12">
                    <NewPagination :pagination="last_logins" page_name="/ostatnie-logowania"></NewPagination>
                    <table class="tw-w-full tw-text-center tw-border-collapse">
                        <thead>
                            <tr class="table-tr">
                                <th
                                    class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                    Imię i nazwisko</th>
                                <th
                                    class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                    Data i godzina logowania</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:tw-bg-grey-lighter" v-for="(item, index) in last_logins.data">
                                <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ item.login_count ?? '-'
                                    }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-border-b tw-border-grey-light">{{ item.date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->
        </div>

    </AdminLayout>
</template>

<style scoped lang="css">
/* Obrót etykiet na osi X o -45° i punkt obrotu bliżej prawego górnego rogu */
::v-deep(.layer-axis-x .tick text) {
    transform: rotate(-45deg);
    /* margin-top: 30px; */
    transform-origin: 0 0%;
    text-anchor: end;
    font-size: 0.9rem;
}
</style>

<!-- <section class="mt-10 overflow-hidden bg-gray-100 rounded shadow-xl sm:rounded-lg"> -->
