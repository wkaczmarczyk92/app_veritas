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
    return !is ? '<i class="fa-light fa-hourglass-start tw-text-xl tw-text-orange-600"></i>' : '<i class="fa-solid fa-circle-check"></i>';
}

console.log(data.value);

</script>

<template>
    <div id="modal"
        class="tw-fixed tw-inset-0 tw-flex tw-items-center tw-justify-center tw-bg-black tw-bg-opacity-50 tw-p-4">
        <div class="tw-bg-white tw-rounded-lg tw-p-6 tw-w-full md:tw-w-4/5 lg:tw-w-1/2">
            <h2 class="tw-text-2xl tw-font-bold tw-mt-3 tw-mb-6 tw-text-gray-800">
                <i class="fa-sharp fa-regular fa-users mr-2"></i>
                Twoje polecenia rodzin
            </h2>

            <div class="tw-flex tw-mt-8" v-if="count > per_page">
                <nav class="tw-flex tw-justify-between">
                    <a href="#"
                        class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                        @click="page > 1 ? page-- : null">Wstecz</a>
                    <a href="#"
                        class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 disabled-paginatio-item">{{
                            page }} z {{ data.last_page }}</a>
                    <a href="#"
                        class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                        @click="page < data.last_page ? page++ : null">Dalej</a>
                </nav>
            </div>

            <div class="table-container tw-overflow-x-auto" v-if="data.data.length">
                <table class="tw-text-center tw-w-full tw-border-collapse tw-mt-4">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                #</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Nazwisko rodziny</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Nr telefonu</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Wypłacono bonus?</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Data dodania</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :class="item.rejected ? 'tw-bg-red-200' : ''" v-for="(item, index) in data.data">
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ item.id }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">
                                {{ item.rejected ? 'dane usunięto' : item.family_last_name }}
                            </td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">
                                {{ item.rejected ? 'dane usunięto' : `+${item.country_code} ${item.phone_number}` }}
                            </td>
                            <td v-if="!item.rejected" class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light"
                                v-html="is_payout(item.bonus_payout_completed)">
                            </td>
                            <td v-else class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">dane usunięto
                            </td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ format(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <AlertInfo v-else>
                Brak poleconych rodzin do wyświetlenia.
            </AlertInfo>

            <div class="tw-flex tw-flex-row tw-justify-end tw-mt-4">
                <PrimaryButton @click="$emit('close')">
                    Zamknij
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>

