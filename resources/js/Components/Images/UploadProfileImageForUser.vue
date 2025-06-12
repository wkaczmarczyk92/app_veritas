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
    'update',
    'show-processing',
    'close-processing'
]);

const submit = () => {
    if (selected_file.value == null) {
        danger.value = 'Wybierz zdjęcie.';
        close_alerts();
        return;
    }

    processing.value = true;
    emit('show-processing')
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


        emit('close-processing')
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
        <input @change="handle_file_change" type="file" id="image" ref="file_input" accept="image/*" name="image"
            class="tw-my-8 tw-appearance-none tw-border tw-rounded-md tw-w-full tw-text-gray-700 tw-leading-tight focus:tw-outline-none focus:tw-shadow-outline tw-hidden">
        <div class="label-wrapper inline-block max-w-max">
            <label for="image" :class="processing ? 'pointer-events-none' : ''">
                <div
                    class="tw-bg-blue-800 tw-text-gray-100 tw-px-6 tw-py-4 tw-rounded-md hover:tw-bg-blue-600 hover:tw-cursor-pointer">
                    <i class="fa-duotone fa-camera-retro tw-mr-2"></i>
                    <span v-if="processing">Przetwarzanie{{ dots }}</span>
                    <span v-else>Wybierz zdjęcie z dysku</span>

                </div>
            </label>
        </div>
    </div>
</template>
