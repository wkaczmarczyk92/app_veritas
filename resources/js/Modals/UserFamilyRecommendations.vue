<script setup>

import axios from 'axios';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
])

const page = ref(1);
const per_page = ref(10);
const count = ref(0);
const data = ref({});

const load = async () => {
    let response = await axios.post(route('user.family.recommendations', {
        user_id: props.user.id
    }), {
        page: page.value
    });

    data.value = response.data;
}

watch(page, async (new_page, old_page) => {
    await load();
})

const count_family_recommendations = async () => {
    let response = await axios.post(route('count.user.family.recommendations', {
        user_id: props.user.id
    }));

    count.value = response.data;
}

await load();
await count_family_recommendations();

const is_payout = (is) => {
    return !is ? '<i class="fa-light fa-hourglass-start text-xl text-orange-600"></i>' : '<i class="fa-solid fa-circle-check"></i>'; 
}

console.log(data.value);

</script>

<template>
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full md:w-4/5 lg:w-1/2">
            <h2 class="text-2xl font-bold mt-3 mb-6 text-gray-800">
                <i class="fa-sharp fa-regular fa-users mr-2"></i>
                Twoje polecenia rodzin
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
                                Nazwisko rodziny</th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Nr telefonu</th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Wypłacono bonus?</th>
                            <th
                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                Data dodania</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :class="item.rejected ? 'bg-red-200' : ''" v-for="(item, index) in data.data">
                            <td class="py-4 px-6 border-b border-grey-light">{{ item.id }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">
                                {{ item.rejected ? 'dane usunięto' : item.family_last_name }}
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">
                                {{ item.rejected ? 'dane usunięto' : `+${item.country_code} ${item.phone_number}` }}
                            </td>
                            <td v-if="!item.rejected" class="py-4 px-6 border-b border-grey-light" v-html="is_payout(item.bonus_payout_completed)">
                            </td>
                            <td v-else class="py-4 px-6 border-b border-grey-light">dane usunięto
                            </td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ format(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <AlertInfo v-else>
                Brak poleconych rodzin do wyświetlenia.
            </AlertInfo>

            <div class="flex flex-row justify-end mt-4">
                <PrimaryButton @click="$emit('close')">
                    Zamknij
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>