<template>
    <Head title="VeritasApp - szukaj ofert" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <section
                    class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg tw-px-6 sm:tw-px-20 tw-pt-16 tw-pb-8 sm:tw-pb-12 tw-mt-10 tw-relative">
                    <div class="tw-flex flex-row tw-justify-center tw-item-center">
                        <h2 class="tw-text-gray-800 tw-text-2xl lg:tw-text-3xl tw-font-bold">
                            Sprawdź przygotowane dla Ciebie oferty!
                        </h2>
                    </div>
                    <Transition name="fade" mode="out-in">
                        <div v-if="!userStore.user?.ready_to_departure_dates"
                            class="tw-mt-6 tw-text-center tw-text-gray-800">
                            <p>Aby sprawdzić oferty przygotowane dla Ciebie, uzupełnij <span
                                    class="tw-text-blue-600 hover:tw-text-blue-800 hover:tw-cursor-pointer font-bold hover:tw-underline"
                                    @click="modalStore.visibility.ready_to_departure = true">datę gotowości do
                                    wyjazdu</span>.</p>
                        </div>
                        <div v-else>
                            <Suspense>
                                <OfferMap v-if="offers == null" @search="search()"
                                    v-model:selected_lands="selected_lands" />
                                <OfferDisplay v-else v-model:submitted="submitted" :offers="offers" :btns="btns"
                                    @update-btns="btns = $event" @search="search()" @clear-search="clear_search()" />
                            </Suspense>
                            <Transition name="fade" mode="out-in">
                            </Transition>
                        </div>
                    </Transition>
                </section>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

import { useModalStore } from '@/Pinia/ModalStore';
import { useUserStore } from '@/Pinia/UserStore';
import { AlertStore } from '@/Pinia/AlertStore';

import OfferDisplay from './Offer.display.vue';
import OfferMap from './Offer.map.vue';

import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const modalStore = useModalStore();
const userStore = useUserStore();
const alertStore = AlertStore();

onMounted(async () => {
    await userStore.set_user(props.user);

})

const selected_lands = ref([]);

const offers = ref(null);
const btns = ref({})
const submitted = ref([]);

const search = async () => {
    if (selected_lands.value.length <= 0) {
        alertStore.pushAlert('danger', 'Wybierz przynajmniej jeden land aby wyszukać oferty.')
        return
    }
    submitted.value = []
    let response = await axios.post(route('crm.offer.get'), {
        lands: selected_lands.value
    })
    response = response.data

    if (response?.alert_type) {
        alertStore.pushAlert(response.alert_type, response.msg)
    } else {
        offers.value = response.offers
    }

    console.log('oferty', offers.value)
    let keys = Object.keys(offers.value).map(id => parseInt(id));
    console.log(keys)

    keys.forEach(id => {
        btns.value[id] = {
            disabled: false,
            text: 'Zgłoś się'
        }
    })
}

const clear_search = () => {
    offers.value = null
    submitted.value = []
}

const so = computed(() => {
    return offers.value == null ? true : false;
})


</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
