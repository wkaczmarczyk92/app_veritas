<script setup>

import { Head } from '@inertiajs/vue3'
import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'
import { AlertStore } from '@/Pinia/AlertStore';
import Processing from '@/Composables/Processing.vue';
import { ref, reactive, onMounted, onUnmounted } from 'vue'
import TInput from '@/Composables/Form/TInput.vue';
import InputError from '@/Components/InputError.vue';
import FileHandler from '@/Composables/Files/FileHandler';

import ImageItem from '@/Components/MediaLibrary/ImageItem.vue';
import AudioItem from '@/Components/MediaLibrary/AudioItem.vue';
import VideoItem from '@/Components/MediaLibrary/VideoItem.vue';

import { useModalStore } from '@/Pinia/ModalV2Store';

import Button from '@/Composables/Buttons/Button.vue';
import MediaLibraryModal from '@/Modals/MediaLibraryModal.vue';

const props = defineProps({
    layout: {
        type: String,
        required: true
    },
    mime_types: {
        type: [Object, Array],
        required: true
    }
})

const layouts = {
    AdminLayout: AdminLayout,
    CourseModeratorLayout: CourseModeratorLayout
}

const modal_store = useModalStore()

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Biblioteka mediów',
        disabled: false,
    },
]

const alert_store = AlertStore()
const active = ref(false)
const files = ref([])
const progress = ref(false)
const errors = ref({})
const current_files = ref({
    files: [],
    end: false,
    files_count: 0
})

const file_handler = reactive(new FileHandler(
    alert_store,
    files.value,
))

const download = (path, filename) => {
    axios.get(path, { responseType: 'blob' })
        .then(response => {
            const blob = new Blob([response.data])
            const link = document.createElement('a')
            link.href = URL.createObjectURL(blob)
            link.download = filename
            link.click()
            URL.revokeObjectURL(link.href)
        }).catch(console.error)
}
const remove_from_files = (index) => {
    file_handler.files.splice(index, 1)
}

function setActive() {
    active.value = true
}
function setInactive() {
    active.value = false
}

async function onDrop(e) {
    progress.value = true
    setInactive()

    if (e.dataTransfer) {
        file_handler.drop(e)
    } else {
        file_handler.file_change(e)
    }

    progress.value = false
}

function preventDefaults(e) {
    e.preventDefault()
}

const events = ['dragenter', 'dragover', 'dragleave', 'drop']
const offset = ref(0)

const get_list = async (offset_num = 0, load = false) => {
    files_display_processing.value.state = true
    files_display_processing.value.text = 'Aktualizacja plików...'

    offset.value = offset_num

    let response = await axios.get(route('media.library.get', {
        offset: offset.value
    }))
    response = response.data
    console.log(response)

    if (!load) {
        current_files.value = response
    } else {
        current_files.value.files.push(...response.files)
        current_files.value.end = response.end
        console.log(current_files.value.files)
    }

    current_files.value.files.forEach((file) => {
        file_handler.type(file)
    })

    files_display_processing.value.state = false
}



onMounted(async () => {
    events.forEach((eventName) => {
        document.body.addEventListener(eventName, preventDefaults)
    })

    await get_list()
})

onUnmounted(() => {
    events.forEach((eventName) => {
        document.body.removeEventListener(eventName, preventDefaults)
    })
})

const submit = async () => {
    progress.value = true
    errors.value = {}

    let form_data = new FormData()
    file_handler.files.forEach((file, i) => {
        form_data.append(`files_data[${i}][file]`, file.file, file.file.name)
        form_data.append(`files_data[${i}][name]`, file.name)
        form_data.append(`files_data[${i}][size]`, file.file.size)
        // form_data.append(`files_data[${i}][opt_for]`, file.opt_for)
        form_data.append(`files_data[${i}][type]`, file.type)
    })

    try {
        let response = await axios.post(route('media.library.store'), form_data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        response = response.data

        if (response.errors) {
            errors.value = response.errors
        }

        if (response.msg) {
            alert_store.pushAlert(response)
        }

        if (response.success) {
            file_handler.files = []
            console.log(file_handler.files)
            await get_list()
        }
    } catch (error) {
        console.log(error)
        alert_store.exception()
    }

    // console.log('response', response)
    progress.value = false
}

const files_display_processing = ref({
    state: false,
    text: 'Usuwanie pliki...'
})

const open_modal = (file) => {
    modal_store.set_option()
    modal_store.add({
        name: 'media_library_modal',
        component: MediaLibraryModal,
        data: file
    })
}

// const remove_file = async (id, index, arr = 'caretaker') => {
//     if (confirm('Czy na pewno chcesz usunąć ten plik?')) {
//         files_display_processing.value.state = true
//         files_display_processing.value.text = 'Usuwanie pliku...'
//         let response = await axios.delete(route('course_moderator.files.destroy', {
//             id: id
//         }))

//         response = response.data

//         alert_store.pushAlert(response)

//         if (response.success) {
//             current_files.value[arr].splice(index, 1)
//         }

//         files_display_processing.value.state = false
//     }
// }

// const test_mime_type = 'application/pdf'

</script>

<template>

    <Head title="VeritasApp - moderator kursów - pliki" />
    <CourseModeratorLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent class="tw-relative">
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-2 tw-mb-4 tw-relative">
                <v-card :loading="false" title="Dodaj nowe pliki" subtitle="wybierz i wrzuć pliki"
                    class="tw-mb-4 tw-shadow-2xl  tw-relative">
                    <Processing v-if="progress" msg="Ładowanie plików..." />
                    <template v-slot:prepend>
                        <i class="fa-light fa-file-arrow-up tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-mt-4 !tw-px-4">
                        <div :data-active="active" @dragenter.prevent="setActive" @dragover.prevent="setActive"
                            @dragleave.prevent="setInactive" @drop.prevent="onDrop">
                            <label for="file" class="hover:tw-cursor-pointer">
                                <div class="tw-flex tw-flex-row tw-bg-gray-200 tw-justify-center tw-py-6 tw-rounded hover:tw-cursor-pointer hover:tw-bg-gray-300 tw-transition tw-duration-300 tw-ease-in-out"
                                    :class="active ? 'tw-bg-gray-300 tw-border-4 tw-border-gray-500 tw-border-dotted' : ''">
                                    Wybierz
                                    lub upuść
                                    pliki
                                    tutaj...
                                </div>
                            </label>
                            <input class="tw-hidden" type="file" id="file" multiple accept="image/*,audio/*,video/*"
                                @change="onDrop($event)">
                        </div>
                    </v-card-text>
                </v-card>
                <v-card :loading="false" title="Wybrane pliki" subtitle="" class="tw-mb-4 tw-shadow-2xl tw-relative">
                    <Processing v-if="progress" msg="Ładowanie plików..." />

                    <template v-slot:prepend>
                        <i class="fa-solid fa-list-ul tw-text-2xl"></i>
                    </template>

                    <!-- <Processing v-if="files_display_processing.state" :msg="files_display_processing.text" /> -->
                    <v-card-text class="tw-mt-4 !tw-px-4">
                        <div v-if="file_handler.get().length > 0" class="tw-mt-4">
                            <div v-for="(file, index) in file_handler.get()" :key="index"
                                class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-4 tw-gap-2 tw-mb-4">
                                <div class="tw-col-span-3">
                                    <TInput v-model:model_value="file.name" class="tw-col-span-2" :clearable="false"
                                        :error="null" density="compact">
                                    </TInput>
                                    <InputError
                                        :message="errors[`files_data.${index}.name`] ? errors[`files_data.${index}.name`][0] : null">
                                    </InputError>
                                </div>
                                <div class="tw-flex tw-flex-row tw-gap-2">
                                    <div class="tw-flex-1">
                                        <TInput v-model:model_value="file.type" class="tw-col-span-2" :error="null"
                                            :clearable="false" density="compact" readonly>
                                            <template v-slot:append-inner>
                                                <i @click="remove_from_files(index)"
                                                    class="fa-solid fa-xmark tw-text-2xl tw-text-red-600 hover:tw-text-red-700 hover:tw-cursor-pointer"></i>
                                            </template>
                                        </TInput>
                                        <InputError
                                            :message="errors[`files_data.${index}.type`] ? errors[`files_data.${index}.type`][0] : (!mime_types.includes(file.type) ? 'Nieprawidłowy mime type.' : null)">
                                        </InputError>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card-text>
                    <v-card-actions v-if="files.length > 0" class="!tw-px-4">
                        <v-btn variant="outlined" color="#16a34a" size="large" @click="submit()" :disabled="progress">
                            <i class="fa-solid fa-plus tw-mr-2"></i>
                            Dodaj pliki
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </div>
            <div class="tw-grid tw-grid-cols-1">
                <v-card :loading="false" title="Biblioteka mediów" subtitle="" class="tw-mb-4 tw-shadow-2xl ">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-photo-film-music tw-text-2xl"></i>
                    </template>

                    <Processing v-if="files_display_processing.state" :msg="files_display_processing.text" />
                    <v-card-text class="tw-mt-4 !tw-px-4">

                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-4"
                            v-if="current_files.files.length > 0">
                            <div v-for="(file, index) in current_files.files" @click="open_modal(file)">
                                <ImageItem v-if="file_handler.type(file) == 'image'" :file="file" />
                                <AudioItem v-else-if="file_handler.type(file) == 'audio'" :file="file" />
                                <VideoItem v-else-if="file_handler.type(file) == 'video'" :file="file" />
                            </div>
                        </div>
                        <div v-else>Brak plików do wyświetlenia</div>
                    </v-card-text>
                    <v-card-actions class="tw-my-4">
                        <div class="tw-flex tw-flex-row tw-justify-center tw-w-full">
                            <Button value="Załaduj więcej" @click="get_list(offset + 10, true)"
                                v-if="!current_files.end" />
                            <div v-else>Wszystkie pliki widoczne</div>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<style>
#drop_zone {
    border: 5px solid blue;
    width: 200px;
    height: 100px;
}
</style>
