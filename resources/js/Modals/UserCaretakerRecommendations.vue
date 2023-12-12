<script setup>

import axios from 'axios';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Icon from '@/Components/Functions/Icon';
import { format } from '@/Components/Functions/DateFormat.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

defineEmits([
    'close'
]);

const spinner_visible = ref(false);
const icon = ref(new Icon);

const page = ref(1);
const data = ref({});
const count = ref(0);
const per_page = ref(10);

watch(page, async (new_page, old_page) => {
    console.log(11);
    await load();
})

const load = async () => {
    let response = await axios.post(route('user.caretaker.recommendations', {
        user_id: props.user.id
    }), {
        page: page.value
    });

    data.value = response.data;
}

const count_caretaker_recommendations = async () => {
    let response = await axios.post(route('count.user.caretaker.recommendations', {
        user_id: props.user.id
    }));

    count.value = response.data;
}

await load();
await count_caretaker_recommendations();
console.log(data.value);




</script>

<template>
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full md:w-4/5 lg:w-1/2">
            <h2 class="text-2xl font-bold mt-3 mb-6 text-gray-800">
                <i class="fa-regular fa-user-group mr-2"></i>
                Twoje polecenia opiekunek
            </h2>

            <div class="flex mt-8" v-if="count > per_page">
                <nav class="flex justify-between">
                    <a href="#"
                        class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200"
                        @click="page > 1 ? page-- : null">Wstecz</a>
                    <a href="#"
                        class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 disabled-paginatio-item">{{
                            page }} z {{ data.last_page }}</a>
                    <a href="#"
                        class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200"
                        @click="page < data.last_page ? page++ : null">Dalej</a>
                </nav>
            </div>

            <div class="table-container overflow-x-auto" v-if="data.data.length">
                <table class="text-center w-full border-collapse mt-4">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                #</th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Gotowe do wypłaty</th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Wypłacono?</th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Data dodania</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-grey-lighter" v-for="(item, index) in data.data">
                            <td class="py-4 px-6 border-b border-grey-light">{{ item.id }}</td>
                            <td 
                                class="py-4 px-6 border-b border-grey-light" 
                                :class="icon._rpt_class(item.bonus_payout_completed, item.ready_to_payout)" 
                                v-html="icon._rpt(item.bonus_payout_completed)">
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">
                                <i v-if="item.bonus_payout_completed" class="fa-solid fa-square-check fa-lg text-green-600"></i>
                                <span v-else>-</span>
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex items-center justify-center spinner-container"
                    :class="spinner_visible ? '' : 'hidden'">
                    <div class="w-10 h-10 border-b-8 border-white-900 rounded-full animate-spin"></div>
                </div>
            </div>
            <AlertInfo v-else>Brak poleconych opiekunek do wyświetlenia.</AlertInfo>


            <div class="flex flex-row justify-end mt-4">
                <PrimaryButton @click="$emit('close')">
                    Zamknij
                </PrimaryButton>
            </div>
        </div>

    </div>
</template>