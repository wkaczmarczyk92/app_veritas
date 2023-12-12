<script setup>

import PrimaryButton from '@/Components/PrimaryButton.vue';
import SButton from '@/Components/SButton.vue';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    user_id: {
        type: Number,
        required: true
    }
})

const emit = defineEmits([
    'update',
    'close'
]);

const file_input = ref(null);
const selected_file = ref(null);

const handle_file_change = () => {
    selected_file.value = file_input.value.files[0];
    console.log(selected_file.value.name);
}

const submit = () => {
    if (selected_file.value == null) {
        danger.value = 'Wybierz zdjęcie.';
        close_alerts();
        return;
    }

    processing.value = true;
    set_processing();

    axios.post(route('store.or.update.user.profile.image'), {
        id: props.user_id,
        file: selected_file.value,
        status: 1
    }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        if (response.data.success) {
            success.value = 'Zdjęcie zostało wgrane i czeka na weryfikację.';
            selected_file.value = null;
            file_input.value = null;

            console.log(response.data.user_profile_image);

            emit('update', response.data.user_profile_image);
        } else {
            danger.value = 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później lub skontaktuj się z administratorem.';
        }

        clearInterval(interval.value);
        interval.value = null
        processing.value = false;

        close_alerts();
    });
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
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full md:w-4/5 lg:w-1/2">
            <h2 class="text-2xl font-bold mb-3 text-gray-800">Zaktualizuj swoje zdjęcie profilowe!</h2>
            <hr class="my-8">
            
            <div class="gap-4 text-center">
            <p v-if="success" class="text-green-700 mb-5">{{ success }}</p>
            <p v-if="danger" class="text-red-700 mb-5">{{ danger }}</p>
                <input 
                    @change="handle_file_change" 
                    type="file" 
                    id="image" 
                    ref="file_input" 
                    accept="image/*" 
                    name="image" 
                    class="my-8 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hidden">
                <div class="label-wrapper inline-block max-w-max">
                    <label for="image" :class="processing ? 'pointer-events-none' : ''">
                        <div class="text-2xl bg-blue-800 text-gray-100 px-6 py-4 rounded-xl hover:bg-blue-600 hover:cursor-pointer">
                            <i class="fa-duotone fa-camera-retro mr-2"></i>
                            <span v-if="processing">Przetwarzanie{{ dots }}</span>
                            <span v-else>Wybierz plik</span>
                            
                        </div>
                    </label>
                </div>
                <p v-if="selected_file && selected_file.name" class="mt-4 text-gray-800">Nazwa pliku: {{ selected_file.name }}</p>
                <p v-else class="mt-4 text-gray-800">Nie wybrano pliku</p>
            </div>
           

  
            <div class="mt-6 text-right">
                <SButton :disabled="processing" id="closeModal" @click="submit()" value="Aktualizuj zdjęcie">
                    
                </SButton>
                <PrimaryButton id="closeModal" @click="$emit('close')">
                    Zamknij
                </PrimaryButton>
                <!-- <button>
                    <div class="flex flex-col justify-center items-center mt-10">
                        <div class="animate-spin rounded-full h-5 w-5 border-t-4 border-b-4 border-gray-900 my-auto"></div>
                    </div>
                </button> -->
            </div>
        </div>
    </div>
</template>

<style scoped>
.fa-secondary{opacity:0.4;}

</style>