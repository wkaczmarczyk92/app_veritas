<script setup>
import { ref, toRef } from 'vue';
import axios from 'axios';

import Header from '@/Components/Templates/Header.vue';
import MButton from '@/Components/Buttons/MButton.vue';

import { AlertStore } from '@/Pinia/AlertStore';


const props = defineProps({
    current_points: {
        type: [null, Number],
        required: true
    },
    user: {
        type: Object,
        required: true
    },
    payout_active: {
        type: Boolean,
        default: false
    }
});

console.log(props.user_has_bonus);

const user = toRef(props.user);

const payout_active = toRef(props.payout_active);

const useAlertStore = AlertStore();

const button_value = ref('Wyślij wniosek o wypłatę');
const btn_disabled = ref(false);

const submit = () => {
    button_value.value = 'Wysyłanie wniosku w toku...';
    btn_disabled.value = true;

    let data = {
        'user_has_bonus': user.value.user_has_bonus
    };

    axios.post(route('payoutrequest.store'), { ...data })
        .then(response => {
            if (response.data?.success == false) {
                useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.');
            }

            if (response.data?.success == true) {
                useAlertStore.pushAlert('success', 'Twój wniosek o wypłatę został wysłany pomyślnie.');
                payout_active.value = true;
                user.value.user_has_bonus = []
            }

            button_value.value = 'Wyślij wniosek o wypłatę';
            btn_disabled.value = false;
        })
}

const count_total_payout_value = () => {
    let return_value = 0;

    user.value.user_has_bonus.forEach((item, index) => {
        return_value += !item.completed ? item.bonus_value : 0;
    })

    return return_value;
}

const anything_to_payout = () => {
    if (user.value.user_has_bonus == null) {
        return false;
    }

    let is = user.value.user_has_bonus.filter(item => {
        return !item.completed && !item.in_progress;
    })

    return is.length > 0 ? true : false;
}



</script>

<template>
    <section
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
                <div v-if="user.user_profiles.level == 1" class="tw-text-center tw-text-md sm:tw-text-lg tw-text-gray-100">
                    Aby wypłacić bonus musisz najpierw osiągnąć BRĄZOWY poziom.
                </div>
                <div class="tw-text-gray-100 tw-text-center tw-text-md sm:tw-text-lg" v-else-if="anything_to_payout()">
                    <span class="tw-text-blue-500 tw-font-bold tw-text-4xl">{{ count_total_payout_value() }} €</span>
                    <div class="tw-mt-4">
                        <span class="tw-text-blue-500 tw-font-bold">Gratulacje!</span> Możesz dokonać wypłaty bonusu
                    </div>
                    <!-- <br class="my-4"> -->
                    <!-- Z Twojego konta zostanie odjęte <span class="text-blue-500 font-bold">{{ user_points_to_sub }} punktów, </span>
                    a Twój nowy stan konta po wypłacie będzie wynosił <span class="text-blue-500 font-bold">{{ user_new_points }} punktów</span> -->
                    <div class="tw-text-center tw-mb-16 tw-mt-4">
                        <MButton :disabled="btn_disabled" @click="submit()" icon="fa-sharp fa-solid fa-euro-sign"
                            bg="tw-bg-transparent" :value="button_value" color="tw-text-blue-500"
                            add_class="tw-px-16 tw-py-6 tw-rounded-2xl neon-after-btn tw-border-blue-500 tw-font-bold tw-text-lg sm:tw-text-2xl tw-relative tw-mt-6">
                        </MButton>

                    </div>

                </div>
                <!-- <div v-else>
                    <div class="text-gray-100 text-sm sm:text-md text-center">
                        Twój aktualny stan konta to <span class="text-blue-500 font-bold">{{ user_current_points }} punktów</span>.
                        Aby dokonać wypłaty bonusu musisz uzbierać minimum <span class="text-blue-500 font-bold">{{ points_to_payout }} punktów</span>.

                    </div>
                </div> -->
            </Transition>
        </div>
        <div v-else class="tw-text-gray-100 tw-text-lg sm:tw-text-xl tw-text-center">Brak aktywnych wniosków do wypłaty i
            brak
            możliwości złożenia wniosku.</div>



    </section>
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
