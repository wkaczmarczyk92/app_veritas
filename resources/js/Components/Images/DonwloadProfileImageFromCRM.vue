<script setup>

import axios from 'axios';
import MButton from '../Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { ref } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits([
    'update',
    'show-processing',
    'close-processing'
]);

const useAlertStore = AlertStore();
const disabled = ref(false);
const btn_value = ref('Pobierz zdjęcie z CRM-a')

const download = async () => {
    disabled.value = true;
    emit('show-processing')
    btn_value.value = 'Pobieranie zdjecia z CRM-a'
    let response = await axios.post(route('download.crm.profile.image', {
        id: props.user.id
    }));

    console.log(response.data)

    if (response.data.success) {
        emit('update', response.data.user_profile_image);
        useAlertStore.pushAlert('success', response.data.msg);
    } else {
        useAlertStore.pushAlert('danger', response.data.msg);
    }

    disabled.value = false;
    emit('close-processing')
    btn_value.value = 'Pobierz zdjęcie z CRM-a'
}

</script>

<template>
    <v-btn color="#86198f" @click="download" :disabled="disabled">
        {{ btn_value }}
    </v-btn>
    <!-- <MButton  icon="fa-solid fa-cloud-arrow-down" :value="btn_value" 
        bg="tw-bg-fuchsia-800" hover="hover:tw-bg-fuchsia-700"></MButton> -->
</template>
