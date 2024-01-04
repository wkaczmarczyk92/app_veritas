<script setup>
import { ref } from 'vue';
import axios from 'axios';

import MButton from '@/Components/Buttons/MButton.vue';

import { AlertStore } from '@/Pinia/AlertStore';

defineProps({
    model_value: {
        type: Boolean,
        required: true
    },
    bonus: {
        type: Object
    }
});

defineEmits([
    'update:model_value'
]);

const useAlertStore = AlertStore();
const button_value = ref('Zgłoś chęć polecenia opiekunki');
const success_msg = ref(false);
const danger_msg = ref(false);
const disabled = ref(false);

const submit = () => {
    button_value.value = 'Zgłaszanie opiekunki w toku...';
    success_msg.value = false;
    danger_msg.value = false;
    disabled.value = true;

    axios.post(route('caretakerrecommendation.store'))
        .then(response => {
            button_value.value = 'Zgłoś chęć polecenia opiekunki';
            let alert_type = response.data.success ? 'success' : 'danger';
            let msg = response.data.success ? 'Twoje polecenie opiekunki zostało zarejestrowane w systemie. Oczekuj na telefon od naszego konsultanta.' : 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.';
            useAlertStore.pushAlert(alert_type, msg);

            disabled.value = false;
        })
}

</script>

<template>
    <section class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg">
        <h2 class="tw-text-xl sm:tw-text-2xl tw-mb-3 tw-text-gray-800 tw-font-bold tw-pr-6 tw-py-6 tw-pl-6">
            <div class="tw-flex tw-flex-row tw-justify-between">
                <span>Poleć opiekunkę</span>
                <i class="fa-regular fa-user-group tw-mr-2"></i>
            </div>
        </h2>
        <hr class="tw-my-6 tw-px-6">
        <div class="tw-text-gray-800 tw-px-6">
            Znasz kogoś kto byłby zainteresowany współpracą z nami jako opiekun/opiekunka? Poleć nam taką osobę i zyskaj
            dodatkowe <span class="tw-text-blue-600 tw-font-bold">{{ bonus.caretaker_recommendation }}€</span>!<br>
            Możesz sprawdzić swoja aktualne polecenia <a href="#" class="tw-underline tw-text-blue-600"
                @click.prevent="$emit('update:model_value', true)">TUTAJ</a>.
        </div>
        <div class="tw-text-center tw-my-16 tw-px-6">
            <MButton @click="submit()" :value="button_value" icon="fa-solid fa-user-plus" bg="tw-bg-gray-800"
                hover="hover:tw-bg-gray-700" class="tw-text-xl" :disabled="disabled"></MButton>
        </div>
        <div>
            <img src="images/image5.png" class="tw-w-1/2 tw-ml-auto tw-mr-auto">
        </div>
    </section>
</template>
