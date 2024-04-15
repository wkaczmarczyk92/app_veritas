<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { AlertStore } from '@/Pinia/AlertStore';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import Processing from '@/Composables/Processing.vue';
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'

import TInput from '@/Composables/Form/TInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    opt_for: {
        type: Object,
        required: true
    }
})

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

const active = ref(false)
const files = ref([])
const progress = ref(false)
const errors = ref({})

const alert_store = AlertStore()

const current_files = ref([])

const remove_from_files = (index) => {
    files.value.splice(index, 1)
}

function setActive() {
    active.value = true
}
function setInactive() {
    active.value = false
}

const file_exists = (file) => {
    return files.value.some(f => f.name === file.name)

}

const push_file = (file) => {
    files.value.push({
        name: file.name.replace(/\.[^/.]+$/, ''),
        size: file.size,
        file: file,
        opt_for: null
    })
}

async function onDrop(e) {
    progress.value = true
    console.log('progress', progress.value)
    setInactive()

    if (e.dataTransfer.items) {
        [...e.dataTransfer.items].forEach((item, i) => {
            if (item.kind === "file") {
                const file = item.getAsFile();

                if (!file_exists(file)) {
                    push_file(file)
                }
            }
        });
    } else {
        [...e.dataTransfer.files].forEach((file, i) => {
            if (!file_exists(file)) {
                push_file(file)
            }
        });
    }

    progress.value = false
}

function preventDefaults(e) {
    e.preventDefault()
}

const events = ['dragenter', 'dragover', 'dragleave', 'drop']

const get_list = async () => {
    files_display_processing.value.state = true
    files_display_processing.value.text = 'Aktualizacja plików...'

    let response = await axios.get(route('course_moderator.files.list'))
    response = response.data

    current_files.value = response
    console.log(current_files.value)

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

const handle_file_change = async (e) => {
    progress.value = true
    await new Promise(r => setTimeout(r, 2000));
    file_change(e)
}

const file_change = (e) => {
    console.log('progress', progress.value)
    let selected_files = e.target.files

    for (let i = 0; i < selected_files.length; i++) {
        if (!file_exists(selected_files[i])) {
            push_file(selected_files[i])
        }
    }

    progress.value = false
}

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Pliki',
        disabled: true,
    }
]

const submit = async () => {
    progress.value = true
    errors.value = {}

    let form_data = new FormData()
    files.value.forEach((file, i) => {
        form_data.append(`files_data[${i}][file]`, file.file, file.file.name)
        form_data.append(`files_data[${i}][name]`, file.name)
        form_data.append(`files_data[${i}][size]`, file.file.size)
        form_data.append(`files_data[${i}][opt_for]`, file.opt_for)
    })

    let response = await axios.post(route('course_moderator.files.update'), form_data, {
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
        files.value = []
        await get_list()
    }

    console.log('response', response)
    progress.value = false
}

const files_display_processing = ref({
    state: false,
    text: 'Usuwanie pliki...'
})

const remove_file = async (id, index, arr = 'caretaker') => {
    if (confirm('Czy na pewno chcesz usunąć ten plik?')) {
        files_display_processing.value.state = true
        files_display_processing.value.text = 'Usuwanie pliku...'
        let response = await axios.delete(route('course_moderator.files.destroy', {
            id: id
        }))

        response = response.data

        alert_store.pushAlert(response)

        if (response.success) {
            current_files.value[arr].splice(index, 1)
        }

        files_display_processing.value.state = false
    }
}

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
                            <input class="tw-hidden" type="file" id="file" multiple
                                @change="handle_file_change($event)">
                        </div>
                        <div v-if="files.length > 0" class="tw-mt-4">
                            <div v-for="(file, index) in files" :key="index"
                                class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-2 tw-mb-4">
                                <div class="tw-col-span-2">
                                    <TInput v-model="file.name" class="tw-col-span-2" :clearabl="false">
                                    </TInput>
                                    <InputError
                                        :message="errors[`files_data.${index}.name`] ? errors[`files_data.${index}.name`][0] : null">
                                    </InputError>
                                </div>
                                <div class="tw-flex tw-flex-row tw-gap-2">
                                    <div class="tw-flex-1">
                                        <v-select clearable label="Dla kogo?" :items="opt_for" item-title="name"
                                            item-value="id" variant="outlined" hide-details
                                            v-model="file.opt_for"></v-select>
                                        <InputError
                                            :message="errors[`files_data.${index}.opt_for`] ? errors[`files_data.${index}.opt_for`][0] : ''">
                                        </InputError>
                                    </div>
                                    <div class="tw-my-auto">
                                        <i @click="remove_from_files(index)"
                                            class="fa-solid fa-xmark tw-text-2xl tw-text-red-600 hover:tw-text-red-700 hover:tw-cursor-pointer"></i>
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
                <v-card :loading="false" title="Lista plików" subtitle="" class="tw-mb-4 tw-shadow-2xl ">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-list-ul tw-text-2xl"></i>
                    </template>

                    <Processing v-if="files_display_processing.state" :msg="files_display_processing.text" />
                    <v-card-text class="tw-mt-4 !tw-px-4">
                        <div class="tw-flex tw-flex-col tw-gap-1"
                            v-if="current_files.caretaker && current_files.caretaker.length > 0">
                            <h2 class="tw-font-bold tw-text-xl">Opiekunka:</h2>
                            <div v-for="(file, index) in current_files.caretaker"
                                class="tw-flex tw-flex-row tw-justify-between hover:tw-bg-gray-200 tw-px-1">
                                <div class="tw-text-lg tw-text-blue-600 hover:tw-text-blue-500 hover:tw-cursor-pointer"
                                    @click="download(file.path, file.name + '.' + file.extension)">
                                    {{ file.name }}
                                </div>
                                <div>
                                    <i class="fa-regular fa-trash tw-text-xl hover:tw-cursor-pointer tw-text-red-600 hover:tw-text-red-800"
                                        @click="remove_file(file.id, index)"></i>
                                </div>

                            </div>
                        </div>
                        <div v-else class="tw-mt-8">
                            <div v-if="!current_files.caretaker">
                                <v-skeleton-loader type="list-item-avatar"></v-skeleton-loader>
                                <v-skeleton-loader type="list-item-avatar"></v-skeleton-loader>
                            </div>
                            <div v-else>
                                <AlertInfo>Brak plików dla opiekunek.</AlertInfo>
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col tw-gap-1 tw-mt-8"
                            v-if="current_files.recruiter && current_files.recruiter.length > 0">
                            <h2 class="tw-font-bold tw-text-xl">Rekruter:</h2>
                            <div v-for="(file, index) in current_files.recruiter"
                                class="tw-flex tw-flex-row tw-justify-between hover:tw-bg-gray-200 tw-px-1">
                                <div class="tw-text-lg tw-text-blue-600 hover:tw-text-blue-500 hover:tw-cursor-pointer"
                                    @click="download(file.path, file.name + '.' + file.extension)">
                                    {{ file.name }}
                                </div>
                                <div>
                                    <i class="fa-regular fa-trash tw-text-xl hover:tw-cursor-pointer tw-text-red-600 hover:tw-text-red-800"
                                        @click="remove_file(file.id, index, 'recruiter')"></i>
                                </div>

                            </div>
                        </div>
                        <div v-else class="tw-mt-8">
                            <div v-if="!current_files.recruiter">
                                <v-skeleton-loader type="list-item-avatar"></v-skeleton-loader>
                                <v-skeleton-loader type="list-item-avatar"></v-skeleton-loader>
                            </div>
                            <div v-else>
                                <AlertInfo>Brak plików dla rekruterów.</AlertInfo>
                            </div>
                        </div>

                    </v-card-text>
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
