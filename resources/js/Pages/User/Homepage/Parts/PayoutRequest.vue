<script setup>
import { ref, toRef } from 'vue';
import axios from 'axios';

import Header from '@/Components/Templates/Header.vue';
import MButton from '@/Components/Buttons/MButton.vue';

import { AlertStore } from '@/Pinia/AlertStore';
import { useUserStore } from '@/Pinia/UserStore';

const userStore = useUserStore();
await userStore.set_user();

const props = defineProps({
    // current_points: {
    //     type: [null, Number],
    //     required: true
    // },
    // user: {
    //     type: Object,
    //     required: true
    // },
    payout_active: {
        type: Boolean,
        default: false
    }
});

// console.log(props.user_has_bonus);

console.log(userStore.user.user_has_bonus)

// const user = toRef(props.user);

const payout_active = toRef(props.payout_active);

const useAlertStore = AlertStore();

const button_value = ref('Wyślij wniosek o wypłatę');
const btn_disabled = ref(false);

const submit = async () => {
    button_value.value = 'Wysyłanie wniosku w toku...';
    btn_disabled.value = true;

    let data = {
        'user_has_bonus': userStore.user.user_has_bonus.filter(item => {
            return item.bonus_status_id == 5
        })
    };

    let response = await axios.post(route('payoutrequest.store'), { ...data })
    response = response.data

    useAlertStore.pushAlert(response)

    if (response.success == true) {
        payout_active.value = true;
        userStore.user.user_has_bonus = []
        await userStore.set_user()
    }

    button_value.value = 'Wyślij wniosek o wypłatę';
    btn_disabled.value = false;
}

const count_total_payout_value = () => {
    let return_value = 0;

    userStore.user.user_has_bonus.forEach((item, index) => {
        return_value += item.bonus_status_id == 5 ? Number(item.bonus_value) : 0;
    })

    return return_value;
}

const anything_to_payout = () => {
    if (userStore.user.user_has_bonus == null) {
        return false;
    }

    let is = userStore.user.user_has_bonus.filter(item => {
        return item.bonus_status_id == 5
    })

    return is.length > 0 ? true : false;

    return userStore.user.user_has_bonus.length > 0
}

</script>

<template>
    <v-card class="!tw-shadow-xl !tw-bg-gray-900 !tw-p-10 tw-mt-10">
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-text-gray-100 tw-justify-center tw-text-4xl">
                <i class="money-bag-icon-custom"></i>
                Wypłać bonus!
            </div>
        </template>
        <v-card-text>
            <div v-if="anything_to_payout() || payout_active">
                <Transition>
                    <div v-if="payout_active" class="tw-text-gray-100 tw-text-center tw-mb-12">
                        <MButton icon="fa-regular fa-hourglass-clock" bg="tw-bg-transparent"
                            value="Wypłata Twojego bonusu jest w trakcie realizacji" color="tw-text-green-400"
                            add_class="tw-px-16 tw-py-6 tw-rounded-2xl neon-after-btn-green tw-border-green-400 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative tw-mt-6">
                        </MButton>
                    </div>
                </Transition>

                <Transition name="bounce">
                    <div v-if="userStore.user.user_profiles.level == 1"
                        class="tw-text-center tw-text-md sm:tw-text-lg tw-text-gray-100">
                        Aby wypłacić bonus musisz najpierw osiągnąć BRĄZOWY poziom.
                    </div>
                    <div class="tw-text-gray-100 tw-text-center tw-text-md sm:tw-text-lg tw-mt-6"
                        v-else-if="anything_to_payout()">
                        <span class="tw-text-blue-500 tw-font-bold tw-text-5xl">{{ count_total_payout_value() }}
                            €</span>
                        <div class="tw-mt-4">
                            <span class="tw-text-blue-500 tw-font-bold">Gratulacje!</span> Możesz dokonać wypłaty bonusu
                        </div>

                        <div class="tw-text-center tw-mb-16 tw-mt-4">
                            <MButton :disabled="btn_disabled" @click="submit()" icon="fa-sharp fa-solid fa-euro-sign"
                                bg="tw-bg-transparent" :value="button_value" color="tw-text-blue-500"
                                add_class="tw-px-16 tw-py-6 tw-rounded-2xl neon-after-btn tw-border-blue-500 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative tw-mt-6">
                            </MButton>
                        </div>
                    </div>
                </Transition>
            </div>
            <div v-else class="tw-text-gray-100 tw-text-lg sm:tw-text-xl tw-text-center">Brak aktywnych wniosków do
                wypłaty
                i
                brak
                możliwości złożenia wniosku.</div>
        </v-card-text>

    </v-card>
    <!-- <section
        class="tw-bg-gradient-to-br tw-from-gray-800 tw-via-gray-900 tw-to-gray-800 tw-overflow-hidden tw-shadow-xl tw-rounded sm:tw-rounded-lg tw-px-6 tw-pt-16 tw-pb-8 tw-mt-10 tw-relative">
        <Header add_class="tw-mb-8" icon="money-bag-icon-custom" icon_color="tw-text-emerald-600" :center="true"
            color="tw-text-gray-100" h="2" value="Wypłać bonus!">
        </Header>

        <div v-if="anything_to_payout() || payout_active">
            <Transition>
                <div v-if="payout_active" class="tw-text-gray-100 tw-text-center tw-mb-12">
                    <MButton icon="fa-regular fa-hourglass-clock" bg="tw-bg-transparent"
                        value="Wypłata Twojego bonusu jest w trakcie realizacji" color="tw-text-green-400"
                        add_class="tw-px-16 tw-py-6 tw-rounded-2xl neon-after-btn-green tw-border-green-400 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative tw-mt-6">
                    </MButton>
                </div>
            </Transition>

            <Transition name="bounce">
                <div v-if="userStore.user.user_profiles.level == 1"
                    class="tw-text-center tw-text-md sm:tw-text-lg tw-text-gray-100">
                    Aby wypłacić bonus musisz najpierw osiągnąć BRĄZOWY poziom.
                </div>
                <div class="tw-text-gray-100 tw-text-center tw-text-md sm:tw-text-lg" v-else-if="anything_to_payout()">
                    <span class="tw-text-blue-500 tw-font-bold tw-text-4xl">{{ count_total_payout_value() }} €</span>
                    <div class="tw-mt-4">
                        <span class="tw-text-blue-500 tw-font-bold">Gratulacje!</span> Możesz dokonać wypłaty bonusu
                    </div>

                    <div class="tw-text-center tw-mb-16 tw-mt-4">
                        <MButton :disabled="btn_disabled" @click="submit()" icon="fa-sharp fa-solid fa-euro-sign"
                            bg="tw-bg-transparent" :value="button_value" color="tw-text-blue-500"
                            add_class="tw-px-16 tw-py-6 tw-rounded-2xl neon-after-btn tw-border-blue-500 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative tw-mt-6">
                        </MButton>
                    </div>
                </div>
            </Transition>
        </div>
        <div v-else class="tw-text-gray-100 tw-text-lg sm:tw-text-xl tw-text-center">Brak aktywnych wniosków do wypłaty
            i
            brak
            możliwości złożenia wniosku.</div>



    </section> -->
</template>

<style>
.bounce-enter-active {
    animation: bounce-in 0.5s;
}

.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}

@keyframes bounce-in {
    0% {
        transform: scale(0);
    }

    50% {
        transform: scale(1.25);
    }

    100% {
        transform: scale(1);
    }
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
