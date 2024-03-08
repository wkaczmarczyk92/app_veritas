<script setup>

import PrimaryButton from '@/Components/PrimaryButton.vue';
import SButton from '@/Components/SButton.vue';
import { ref } from 'vue';
import axios from 'axios';


import { useModalStore } from '@/Pinia/ModalStore';
import { useUserStore } from '@/Pinia/UserStore';


const userStore = useUserStore();
const modalStore = useModalStore();

await userStore.set_user();

// const props = defineProps({
//     user_id: {
//         type: Number,
//         required: true
//     }
// })

// const emit = defineEmits([
//     'update',
//     'close'
// ]);

const file_input = ref(null);
const selected_file = ref(null);

const handle_file_change = () => {
    selected_file.value = file_input.value.files[0];
    console.log(selected_file.value.name);
}

const submit = async () => {
    if (selected_file.value == null) {
        danger.value = 'Wybierz zdjęcie.';
        close_alerts();
        return;
    }

    processing.value = true;
    set_processing();

    let response = await axios.post(route('store.or.update.user.profile.image'), {
        id: userStore.user.id,
        file: selected_file.value,
        status: 1
    }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })

    if (response.data.success) {
        success.value = 'Zdjęcie zostało wgrane i czeka na weryfikację.';
        selected_file.value = null;
        file_input.value = null;

        userStore.user_profile_image = response.data.user_profile_image;
    } else {
        danger.value = 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później lub skontaktuj się z administratorem.';
    }

    clearInterval(interval.value);
    interval.value = null
    processing.value = false;

    close_alerts();
}

const success = ref(false);
const danger = ref(false);

const close_alerts = () => {
    setTimeout(() => {
        success.value = false;
        danger.value = false;
    }, 5000);
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
    <div class="tw-bg-white tw-rounded-lg p-6 tw-w-full">
        <h2 class="tw-text-xl lg:tw-text-2xl tw-font-bold tw-mb-3 tw-text-gray-800">Zaktualizuj swoje zdjęcie profilowe!
        </h2>
        <hr class="tw-my-8">

        <div class="tw-gap-4 tw-text-center">
            <p v-if="success" class="tw-text-green-700 tw-mb-5">{{ success }}</p>
            <p v-if="danger" class="tw-text-red-700 tw-mb-5">{{ danger }}</p>
            <input @change="handle_file_change" type="file" id="image" ref="file_input" accept="image/*" name="image"
                class="tw-my-8 tw-appearance-none tw-border tw-rounded tw-w-full tw-py-2 tw-px-3 tw-text-gray-700 tw-leading-tight focus:tw-outline-none focus:tw-shadow-outline tw-hidden">
            <div class="label-wrapper tw-inline-block tw-max-w-max">
                <label for="image" :class="processing ? 'tw-pointer-events-none' : ''">
                    <div
                        class="tw-text-2xl tw-bg-blue-800 tw-text-gray-100 tw-px-6 tw-py-4 tw-rounded-xl hover:tw-bg-blue-600 hover:tw-cursor-pointer">
                        <i class="fa-duotone fa-camera-retro mr-2"></i>
                        <span v-if="processing">Przetwarzanie{{ dots }}</span>
                        <span v-else>Wybierz plik</span>

                    </div>
                </label>
            </div>
            <p v-if="selected_file && selected_file.name" class="tw-mt-4 tw-text-gray-800">Nazwa pliku: {{
                selected_file.name
            }}</p>
            <p v-else class="tw-mt-4 tw-text-gray-800">Nie wybrano pliku</p>
        </div>

        <div class="tw-mt-6 tw-text-right">
            <SButton :disabled="processing" id="closeModal" @click="submit()" value="Aktualizuj zdjęcie">

            </SButton>
            <PrimaryButton id="closeModal" @click="modalStore.visibility.profile_image = false">
                Zamknij
            </PrimaryButton>
        </div>
    </div>
</template>

<style scoped>
.fa-secondary {
    opacity: 0.4;
}
</style>
