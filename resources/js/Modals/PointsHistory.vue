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
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg w-full md:w-4/5 lg:w-1/2">
            <div class="bg-gray-100 shadow-xl rounded p-6">
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fa-solid fa-chart-simple text-orange"></i>
                    Historia punktów
                </h2>

                <div class="flex mt-8" v-if="points_record_count > per_page">
                    <nav class="flex justify-between">
                        <a href="#"
                            class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200"
                            @click="current_page > 1 ? current_page-- : null">Wstecz</a>
                        <a href="#"
                            class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 disabled-paginatio-item">{{
                                current_page }} z {{ max_page }}</a>
                        <a href="#"
                            class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200"
                            @click="current_page < max_page ? current_page++ : null">Dalej</a>
                    </nav>
                </div>
                <div class="table-container overflow-x-auto" v-if="points.length">
                    <table class="text-center w-full border-collapse mt-4">
                        <thead>
                            <tr class="table-tr">
                                <th
                                    class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    #</th>
                                <th
                                    class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Punkty</th>
                                <th
                                    class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Dni</th>
                                <th
                                    class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Komentarz</th>
                                <th
                                    class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                    Data dodania</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-grey-lighter" v-for="(item, index) in points">
                                <td class="py-4 px-6 border-b border-grey-light">{{ index + 1 }}</td>
                                <td class="py-4 px-6 border-b border-grey-light"
                                    v-html="displayPoints(item.points, parseInt(item.type))"></td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ item.days ?? '-' }}</td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ item.comment ?? '-' }}
                                </td>
                                <td class="py-4 px-6 border-b border-grey-light">{{ format(item.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex items-center justify-center spinner-container"
                        :class="spinner_visible ? '' : 'hidden'">
                        <div class="w-10 h-10 border-b-8 border-white-900 rounded-full animate-spin"></div>
                    </div>
                </div>
                <AlertInfo v-else>
                    Brak historii do wyświetlenia.
                </AlertInfo>
                <div class="mt-6 text-right">
                    <PrimaryButton id="closeModal" @click="$emit('close')">
                        Zamknij
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>



  