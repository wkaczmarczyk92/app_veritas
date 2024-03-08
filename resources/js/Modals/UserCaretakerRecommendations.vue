<script setup>

import axios from 'axios';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Icon from '@/Components/Functions/Icon';
import { format } from '@/Components/Functions/DateFormat.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import { useModalStore } from '@/Pinia/ModalStore';
import { useUserStore } from '@/Pinia/UserStore';

const userStore = useUserStore();
const modalStore = useModalStore();

await userStore.set_user();

const spinner_visible = ref(false);
const icon = ref(new Icon);

const page = ref(1);
const data = ref({});
const count = ref(0);
const per_page = ref(10);

watch(page, async (new_page, old_page) => {
    await load();
})

const load = async () => {
    let response = await axios.post(route('user.caretaker.recommendations', {
        user_id: userStore.user.id
    }), {
        page: page.value
    });

    data.value = response.data;
}

const count_caretaker_recommendations = async () => {
    let response = await axios.post(route('count.user.caretaker.recommendations', {
        user_id: userStore.user.id
    }));

    count.value = response.data;
}

await load();
await count_caretaker_recommendations();

</script>

<template>
    <div class="tw-bg-white tw-rounded-lg tw-p-6 tw-w-full">
        <h2 class="tw-text-2xl tw-font-bold tw-mt-3 tw-mb-6 tw-text-gray-800">
            <i class="fa-regular fa-user-group tw-mr-2"></i>
            Twoje polecenia opiekunek
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
                            Gotowe do wypłaty</th>
                        <th
                            class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                            Wypłacono?</th>
                        <th
                            class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                            Data dodania</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:tw-bg-grey-lighter" v-for="(item, index) in data.data">
                        <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ item.id }}</td>
                        <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light"
                            :class="icon._rpt_class(item.bonus_payout_completed, item.ready_to_payout)"
                            v-html="icon._rpt(item.bonus_payout_completed)">
                        </td>
                        <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">
                            <i v-if="item.bonus_payout_completed"
                                class="fa-solid fa-square-check fa-lg tw-text-green-600"></i>
                            <span v-else>-</span>
                        </td>
                        <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ format(item.created_at) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="tw-flex tw-items-center tw-justify-center tw-spinner-container"
                :class="spinner_visible ? '' : 'tw-hidden'">
                <div class="tw-w-10 tw-h-10 tw-border-b-8 tw-border-white-900 tw-rounded-full tw-animate-spin"></div>
            </div>
        </div>
        <AlertInfo v-else>Brak poleconych opiekunek do wyświetlenia.</AlertInfo>


        <div class="tw-flex tw-flex-row tw-justify-end tw-mt-4">
            <PrimaryButton @click="modalStore.visibility.caretaker_recommendations = false">
                Zamknij
            </PrimaryButton>
        </div>
    </div>
</template>
