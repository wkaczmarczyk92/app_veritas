<script setup>

import { AlertStore } from '@/Pinia/AlertStore';
import { ref } from 'vue';
import axios from 'axios';

const file_input = ref(null);
const selected_file = ref(null);

const useAlertStore = AlertStore();

const handle_file_change = () => {
    selected_file.value = file_input.value.files[0];
    console.log(selected_file.value.name);
    submit();
}

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

// console.log(props.user);

const emit = defineEmits([
    'update'
]);

const submit = () => {
    if (selected_file.value == null) {
        danger.value = 'Wybierz zdjęcie.';
        close_alerts();
        return;
    }

    processing.value = true;
    set_processing();

    axios.post(route('store.or.update.user.profile.image'), {
        id: props.user.id,
        file: selected_file.value,
        status: 3,
        accepted_by_user_id: true
    }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        console.log(response);
        if (response.data.success) {
            useAlertStore.pushAlert('success', 'Zdjęcie zostało wgrane.');
            selected_file.value = null;
            file_input.value = null;

            emit('update', response.data.user_profile_image);
        } else {
            useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później lub skontaktuj się z administratorem.');
            // danger.value = 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później lub skontaktuj się z administratorem.';
        }

        clearInterval(interval.value);
        interval.value = null
        processing.value = false;

        // close_alerts();
    });
}

const processing = ref(false);
const interval = ref(null);
const dots = ref('.');
const set_processing = () => {
    interval.value = setInterval(() => {
        dots.value = dots.value.length == 3 ? '.' : dots.value + '.';
    }, 1000);
}


</script>

<template>
    <div>
        <input 
            @change="handle_file_change" 
            type="file" 
            id="image" 
            ref="file_input" 
            accept="image/*" 
            name="image" 
            class="my-8 appearance-none border rounded-md w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline hidden">
        <div class="label-wrapper inline-block max-w-max">
            <label for="image" :class="processing ? 'pointer-events-none' : ''">
                <div class="bg-blue-800 text-gray-100 px-6 py-4 rounded-md hover:bg-blue-600 hover:cursor-pointer">
                    <i class="fa-duotone fa-camera-retro mr-2"></i>
                    <span v-if="processing">Przetwarzanie{{ dots }}</span>
                    <span v-else>Wybierz zdjęcie z dysku</span>
                    
                </div>
            </label>
        </div>
    </div>
</template>