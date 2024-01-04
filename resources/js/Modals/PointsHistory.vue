<script setup>

import axios from 'axios';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import { format } from '@/Components/Functions/DateFormat.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const emit = defineEmits([
    'close'
]);

const points = ref('');
const current_page = ref(1);

const count_records = async () => {
    let response = await axios.post(route('count.user.points.records', {
        user_id: props.user.id
    }));
    console.log('dane', response);
    return response.data;
}

watch(current_page, (new_value, old_value) => {
    userPoints(new_value);
})

const spinner_visible = ref(false);

const points_record_count = ref(await count_records());

const per_page = 10;
var max_page = Math.ceil(points_record_count.value / per_page);



async function userPoints(page) {
    let data = {
        current_page: page,
        user_id: props.user.id
    };

    let response = await axios.post('/punkty', data)
    points.value = response.data.user_points;
    current_page.value = page;
}

await userPoints(1);

function displayPoints(points, type) {
    return [1, 3].includes(type) == 1 ? `<span class="add-points">${points}+</span>` : `<span class="sub-points">-${points}</span>`;
}

function isAuto(item) {
    return item.auto ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>';
}



</script>


<template>
    <div id="modal"
        class="tw-fixed tw-inset-0 tw-flex tw-items-center tw-justify-center tw-bg-black tw-bg-opacity-50 tw-p-4">
        <div class="tw-bg-white tw-rounded-lg tw-w-full md:tw-w-4/5 lg:tw-w-1/2">
            <div class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-p-6">
                <h2 class="tw-font-semibold tw-text-xl tw-leading-tight">
                    <i class="fa-solid fa-chart-simple text-orange"></i>
                    Historia punktów
                </h2>

                <div class="tw-flex tw-mt-8" v-if="points_record_count > per_page">
                    <nav class="tw-flex tw-justify-between">
                        <a href="#"
                            class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                            @click="current_page > 1 ? current_page-- : null">Wstecz</a>
                        <a href="#"
                            class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 disabled-paginatio-item">{{
                                current_page }} z {{ max_page }}</a>
                        <a href="#"
                            class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                            @click="current_page < max_page ? current_page++ : null">Dalej</a>
                    </nav>
                </div>
                <div class="table-container tw-overflow-x-auto" v-if="points.length">
                    <table class="tw-text-center tw-w-full tw-border-collapse tw-mt-4">
                        <thead>
                            <tr class="table-tr">
                                <th
                                    class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                    #</th>
                                <th
                                    class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                    Punkty</th>
                                <th
                                    class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                    Dni</th>
                                <th
                                    class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                    Komentarz</th>
                                <th
                                    class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                    Data dodania</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:tw-bg-grey-lighter" v-for="(item, index) in points">
                                <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ index + 1 }}</td>
                                <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light"
                                    v-html="displayPoints(item.points, parseInt(item.type))"></td>
                                <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ item.days ?? '-' }}</td>
                                <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ item.comment ?? '-' }}
                                </td>
                                <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ format(item.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="tw-flex tw-items-center tw-justify-center tw-spinner-container"
                        :class="spinner_visible ? '' : 'tw-hidden'">
                        <div class="tw-w-10 tw-h-10 tw-border-b-8 tw-border-white-900 tw-rounded-full tw-animate-spin">
                        </div>
                    </div>
                </div>
                <AlertInfo v-else class="tw-mt-8">
                    Brak historii do wyświetlenia.
                </AlertInfo>
                <div class="tw-mt-6 tw-text-right">
                    <PrimaryButton id="closeModal" @click="$emit('close')">
                        Zamknij
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>



