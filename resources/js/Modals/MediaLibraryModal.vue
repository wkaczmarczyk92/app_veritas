<script setup lang="ts">

import { ref, watch, onMounted } from 'vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import { useClipboard } from '@vueuse/core'
import { format } from '@/Components/Functions/DateFormat.vue';
import { useModalStore } from '@/Pinia/ModalStore';
import FileHandler from '@/Composables/Files/FileHandler';

import TInput from '@/Composables/Form/TInput.vue';
import Button from '@/Composables/Buttons/Button.vue';
import Loader from '@/Components/Loader.vue';
import Processing from '@/Composables/Processing.vue';

import { AlertStore } from '@/Pinia/AlertStore';

const modalStore = useModalStore();
const alert_store = AlertStore()

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})

console.log(props.data)

const file_handler = new FileHandler()
const file = ref(props.data)
const processing = ref(false)

const { text, copy, copied, isSupported } = useClipboard({ legacy: true })
const copy_to_clipboard = () => {
    if (copy(file.value.hash)) {
        alert_store.pushAlert('success', 'Skopiowano hash pliku do schowka')
    } else {
        alert_store.pushAlert('danger', 'Nie udało się skopiować hash pliku do schowka')
    }
}

const update = async () => {
    processing.value = true

    try {
        let response = await axios.patch(route('media.library.update'), {
            file: file.value
        })

        response = response.data



        alert_store.pushAlert(response)
    } catch (error) {
        alert_store.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później')
    }

    processing.value = false
}

</script>

<template>
    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800">Zgłoszenia do Biura Obsługi Klienta</h2>
    <!-- <div class="modal-body"> -->
    <div class="tw-grid tw-grid-cols-5 tw-mt-4 tw-gap-4 tw-z-10">
        <div class="tw-col-span-3">
            <div v-if="file_handler.type(file) == 'image'"
                class="tw-flex tw-flex-row tw-justify-center tw-items-center">
                <img :src="file.path" alt="" class="">
            </div>
            <div v-else-if="file_handler.type(file) == 'audio'"
                class="tw-flex tw-flex-row tw-justify-center tw-items-center">
                <audio controls class="tw-z-10">
                    <source :src="file.path" :type="file.mime_type.type">
                </audio>

            </div>
            <div v-else-if="file_handler.type(file) == 'video'"
                class="tw-flex tw-flex-row tw-justify-center tw-items-center">
                <video class="tw-w-full tw-z-20" controls>
                    <source :src="file.path">
                </video>
            </div>
        </div>
        <div class="tw-col-span-2">
            <h2 class="tw-text-xl tw-font-bold">Dane pliku</h2>
            <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-4 tw-relative">
                <Processing v-if="processing" msg="Aktualizacja pliku..." />
                <TInput v-model:model_value="file.name" :error="null" />
                <div class="tw-flex tw-flex-row tw-gap-2 tw-mt-4">
                    <p>Hash pliku:</p>
                    <i class="fa-solid fa-copy hover:tw-cursor-pointer tw-text-blue-600 hover:tw-text-blue-500"
                        v-if="file.hash" @click="copy_to_clipboard()"></i>
                    <p class="tw-text-gray-800">{{ file.hash ?? 'brak' }}</p>
                </div>
                <div class="tw-flex tw-flex-row tw-gap-2">
                    <p>Rozszerzenie pliku:</p>
                    <p class="tw-text-gray-800">{{ file.extension ?? 'brak' }}</p>
                </div>
                <div class="tw-flex tw-flex-row tw-gap-2">
                    <p>MIME type pliku:</p>
                    <p class="tw-text-gray-800">{{ file.extension ?? 'brak' }}</p>
                </div>
                <div class="tw-flex tw-flex-row tw-gap-2">
                    <p>Dodane przez:</p>
                    <p class="tw-text-gray-800">
                        <span v-if="file.user && file.user_profiles">
                            {{ file.user.user_profiles.first_name ?? '' }} {{
                                file.user.user_profiles.last_name ?? '' }}
                        </span>
                        <span v-else>
                            {{ file.user.email }}
                        </span>
                    </p>
                </div>
                <div class="tw-flex tw-flex-row tw-gap-2 tw-mt-4">
                    <Button value="Aktualizuj plik" @click="update()" />
                    <Button v-if="!file.hash" value="Wygeneruj hash" color="rose" />
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="tw-grid tw-grid-cols-4">
            <div class="tw-cols-span-3">
                sdfadsf
            </div>
            <div class="tw-cols-span-1">
                sdfadsf
            </div> -->
    <!-- </div> -->
</template>
