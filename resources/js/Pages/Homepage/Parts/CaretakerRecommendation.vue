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
    
    <section class="bg-gray-100 overflow-hidden shadow-xl rounded-lg">
        <h2 class="text-xl sm:text-2xl mb-3 text-gray-800 font-bold pr-6 py-6 pl-6">
            <div class="flex flex-row justify-between">                
                <span>Poleć opiekunkę</span>
                <i class="fa-regular fa-user-group mr-2"></i>
            </div>
        </h2>
        <hr class="my-6 px-6">
        <div class="text-gray-800 px-6">
            Znasz kogoś kto byłby zainteresowany współpracą z nami jako opiekun/opiekunka? Poleć nam taką osobę i zyskaj dodatkowe <span class="text-blue-600 font-bold">{{ bonus.caretaker_recommendation }}€</span>!<br>
            Możesz sprawdzić swoja aktualne polecenia <a href="#" class="underline text-blue-600" @click.prevent="$emit('update:model_value', true)">TUTAJ</a>.
        </div>
        <div class="text-center my-16 px-6">
            <MButton
                @click="submit()"
                :value="button_value"
                icon="fa-solid fa-user-plus"
                bg="bg-gray-800"
                hover="hover:bg-gray-700"
                class="text-xl"
                :disabled="disabled"
            ></MButton>
        </div>
        <div>
            <img src="images/image5.png" class="w-1/2 ml-auto mr-auto">
        </div>
    </section>
</template>