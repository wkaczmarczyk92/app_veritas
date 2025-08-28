<template>
    <v-card variant="tonal" :color="'white'" class="tw-shadow-2xl">
        <v-card-title class="tw-text-gray-800 !tw-pb-0">
            <span class="tw-text-lg tw-font-bold">Ostatnie logowania:</span>
        </v-card-title>
        <v-card-subtitle opacity="1" class="tw-text-gray-600">
            <Link value="Pokaż wszystkie" url="last.login.index" />
        </v-card-subtitle>

        <v-card-text>
            <Responsive class="w-full">
                <template #main="{ width }">
                    <Chart :size="{ width, height: 200 }" :data="data" :margin="margin" :axis="axis"
                        :direction="direction">
                        <template #layers>
                            <Grid strokeDasharray="2,2" />
                            <Line :dataKeys="['name', 'login_count']" />
                        </template>
                        <template #widgets>
                            <Tooltip borderColor="#48CAE4" :config="{
                                name: { hide: true },
                                login_count: { label: '', color: '#0077b6' },
                            }" />
                        </template>
                    </Chart>
                </template>
            </Responsive>
            <!-- <div class="tw-flex tw-flex-row tw-text-sm hover:tw-bg-gray-300" v-for="(item, date) in last_logins"
                :key="date" :class="item.max ? 'tw-font-bold tw-text-blue-700' : 'tw-text-gray-800'">
                <div class="tw-grow">
                    {{ date }}
                </div>
                <div class="tw-text-right tw-grow">
                    {{ item.count }}
                </div>
            </div> -->
        </v-card-text>
    </v-card>
</template>

<script setup>
import { ref } from 'vue'
import Link from '@/Composables/Link.vue'
import { Responsive, Chart, Line, Grid, Tooltip } from 'vue3-charts'

const props = defineProps({
    last_logins: {
        type: [Array, Object],
        default: []
    }
})

console.log('props.last_logins', props.last_logins)

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
    top: 0,
    right: 0,
    bottom: 30
})

</script>
<style scoped lang="css">
/* Obrót etykiet na osi X o -45° i punkt obrotu bliżej prawego górnego rogu */
::v-deep(.layer-axis-x .tick text) {
    transform: rotate(-45deg);
    /* margin-top: 30px; */
    transform-origin: 0 0%;
    text-anchor: end;
    font-size: 10px;
    color: black;
}

::v-deep(.layer-axis-y .tick text) {
    font-size: 10px;
    color: black;
}
</style>