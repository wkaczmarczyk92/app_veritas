<script setup>

import { Head } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

import TInput from '@/Composables/Form/TInput.vue'

const props = defineProps({
    company_branches: Object,
    roles: Object
})

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('course_moderator.users')
    },
    {
        title: 'Dodaj użytkownika',
        disabled: true
    }
]

const form_init = () => {
    return {
        processing: false,
        data: {
            first_name: '',
            last_name: '',
            email: '',
            password: '',
            password_confirmation: '',
            password_proposal: '',
            crm_id: '',
            send_email: true,
            roles: [],
            company_branches: []
        }
    }
}

const form = ref(form_init())
const alert_store = AlertStore()

const user_found = ref(false)

const search_in_crm = async () => {
    form.value.processing = true
    errors.value = {}

    let response = await axios.post(route('course_moderator.crm_recruiter.search'), {
        ...form.value.data
    })

    response = response.data.recruiter
    console.log(response)

    if (response) {
        user_found.value = true

        form.value.data.first_name = response.usr_first_name
        form.value.data.last_name = response.usr_last_name
        form.value.data.email = response.usr_email
        form.value.data.crm_id = response.usr_id_user

        await generate_password(false);

        setTimeout(() => {
            user_found.value = false
        }, 2000)
    } else {
        alert_store.pushAlert('danger', 'Nie znaleziono rekrutera.')
    }

    form.value.processing = false
}

const show_password = ref(false)
const password_tooltip = ref({
    text: 'Pokaż hasło',
    icon: 'fa-solid fa-eye'
})

watch(
    () => form.value.data.email,
    (value) => {
        user_exists.value = false
    }
)

watch(show_password, (value) => {
    if (value) {
        password_tooltip.value.text = 'Ukryj hasło'
        password_tooltip.value.icon = 'fa-solid fa-eye-slash'
    } else {
        password_tooltip.value.text = 'Pokaż hasło'
        password_tooltip.value.icon = 'fa-solid fa-eye'
    }
})

const generate_password = async (processing = true) => {
    if (processing) {
        form.value.processing = true
    }

    let response = await axios.get(route('password.generate'))
    form.value.data.password = form.value.data.password_confirmation = form.value.data.password_proposal = response.data.password

    if (processing) {
        form.value.processing = false
    }

    console.log('form after pass generation', form.value)
}

const errors = ref({})
const user_exists = ref(false)

const submit = async () => {
    form.value.processing = true
    errors.value = {}
    let stop = false;

    let response = await axios.post(route('course_moderator.user.store'), {
        ...form.value.data
    })

    response = response.data

    if (response.errors) {
        console.log('errors', response.errors)
        errors.value = response.errors
        form.value.processing = false
        stop = true
    }

    if (response.user) {
        user_exists.value = true
        form.value.processing = false
        stop = true
    }

    if (stop) {
        return
    }

    if (response.success) {
        form.value = form_init()
    }

    alert_store.pushAlert(response.alert_type, response.msg)

    form.value.processing = false
}

const passwords_match = () => {
    return form.value.data.password === form.value.data.password_proposal &&
        form.value.data.password_confirmation === form.value.data.password_proposal &&
        form.value.data.password_proposal != ''
}

const copied = ref(false)

const copy = () => {
    const textArea = document.createElement("textarea")
    textArea.value = form.value.data.password_proposal

    // Move textarea out of the viewport so it's not visible
    textArea.style.position = "absolute"
    textArea.style.left = "-999999px"

    document.body.prepend(textArea)
    textArea.select()

    try {
        document.execCommand('copy')
        copied.value = true

        setTimeout(() => {
            copied.value = false
        }, 2000)

    } catch (error) {
        console.error(error)
    } finally {
        textArea.remove()
    }
}

</script>

<template>

    <Head title="VeritasApp - moderator kursów - dodaj użytkownika" />
    <CourseModeratorLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1">
                <v-card :loading="form.processing" title="Dodaj nowego użytkownika"
                    subtitle="utwórz konto dla rekrutera lub pobierz dane z CRM" class="!tw-p-4">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-user-plus tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-mt-6 !tw-pb-0">

                        <div class="tw-flex tw-flex-row tw-items-center tw-gap-2 tw-text-blue-600">
                            <i class="fa-regular fa-circle-info"></i>
                            <span>Aby wyszukać rekrutera w CRM uzupełnij adres e-mail LUB CRM ID</span>
                        </div>

                        <Transition>
                            <div class="tw-text-green-600" v-if="user_found">
                                Użytkownik został znaleziony!
                            </div>
                        </Transition>

                        <Transition>
                            <div class="tw-text-red-600" v-if="user_exists">
                                Użytkownik istnieje już w bazie danych. Kliknij <span><a href="#"
                                        class="tw-text-blue-600 tw-font-bold hover:tw-text-blue-500 hover:tw-underline tw-transition-all tw-duration-200 tw-ease-in">TUTAJ</a></span>
                                aby przejść na jego profil.
                            </div>
                        </Transition>

                        <h2 class="v-card-title !tw-px-0 !tw-pb-0">Dane logowania</h2>
                        <TInput label="Adres e-mail" v-model:model_value="form.data.email"
                            placeholder="wpisz nazwisko..." :error="errors.email ? errors.email[0] : null"
                            @keyup.enter="submit()">
                            <template v-slot:append>
                                <v-tooltip text="Szukaj w CRM" location="top">
                                    <template v-slot:activator="{ props }">
                                        <v-btn v-bind="props" class="!tw-min-w-fit !tw-px-4" size="x-large"
                                            color="#2563eb" variant="tonal" @click="search_in_crm()"
                                            :disabled="(form.data.email == '' && form.data.crm_id == '') || (form.data.email != '' && form.data.crm_id != '')">
                                            <i class="fa-solid fa-user-magnifying-glass"></i>
                                        </v-btn>
                                    </template>
                                </v-tooltip>
                            </template>
                        </TInput>
                        <TInput label="CRM ID" v-model:model_value="form.data.crm_id" class="tw-mb-4"
                            placeholder="wpisz id rekrutera z CRM..." :error="errors.crm_id ? errors.crm_id[0] : null"
                            @keyup.enter="submit()" />


                        <Transition>
                            <div v-if="passwords_match()">
                                Wygenerowane hasło:
                                <span
                                    class="tw-text-blue-600 tw-font-bold tw-bg-gray-200 tw-py-2 tw-px-4 tw-rounded-xl">
                                    {{ form.data.password_proposal }}
                                </span>

                            </div>
                        </Transition>
                        <Transition>
                            <p v-if="copied" class="tw-text-green-600">Hasło zostało skopiowane!</p>
                        </Transition>

                        <TInput label="Hasło" v-model:model_value="form.data.password" placeholder="wpisz hasło..."
                            :error="errors.password ? errors.password[0] : null"
                            :type="show_password ? 'text' : 'password'" @keyup.enter="submit()">
                            <template v-slot:append>
                                <v-tooltip :text="password_tooltip.text" location="top">
                                    <template v-slot:activator="{ props }">
                                        <v-btn v-bind="props" size="x-large" color="#475569" variant="tonal"
                                            @click="show_password = !!!show_password" class="!tw-min-w-fit !tw-px-4">
                                            <i :class="password_tooltip.icon"></i>
                                        </v-btn>
                                    </template>
                                </v-tooltip>
                                <v-tooltip text="Kopiuj hasło" location="top">
                                    <template v-slot:activator="{ props }">
                                        <v-btn v-bind="props" size="x-large" color="#0284c7" variant="tonal"
                                            class="tw-ml-2 !tw-min-w-fit !tw-px-4" @click="copy()">
                                            <i class="fa-solid fa-copy"></i>
                                        </v-btn>
                                    </template>
                                </v-tooltip>
                                <v-tooltip text="Generuj hasło" location="top">
                                    <template v-slot:activator="{ props }">
                                        <v-btn v-bind="props" size="x-large" color="#e11d48" variant="tonal"
                                            class="tw-ml-2 !tw-min-w-fit !tw-px-4" @click="generate_password()">
                                            <i class="fa-solid fa-key"></i>
                                        </v-btn>
                                    </template>
                                </v-tooltip>
                            </template>
                        </TInput>

                        <TInput label="Powtórz hasło" v-model:model_value="form.data.password_confirmation"
                            placeholder="wpisz hasło jeszcze raz..."
                            :error="errors.password_confirmation ? errors.password_confirmation[0] : null"
                            :type="show_password ? 'text' : 'password'" @keyup.enter="submit()" />

                        <v-divider :thickness="4" class="border-opacity-100 tw-my-6"></v-divider>

                        <h2 class="v-card-title !tw-px-0 !tw-pb-0">Dane personalne</h2>

                        <TInput label="Imię" v-model:model_value="form.data.first_name" placeholder="wpisz imię..."
                            :error="errors.first_name ? errors.first_name[0] : null" @keyup.enter="submit()" />

                        <TInput label="Nazwisko" v-model:model_value="form.data.last_name"
                            placeholder="wpisz nazwisko..." :error="errors.last_name ? errors.last_name[0] : null"
                            @keyup.enter="submit()" />


                        <h2 class="v-card-title !tw-px-0 !tw-pb-0 !tw-text-base !tw-mt-4">Przypisz role i oddziały do
                            użytkownika
                            (opcjonalnie)
                        </h2>

                        <v-select clearable chips label="Wybierz role" :items="roles" multiple item-title="name_pl"
                            item-value="id" variant="outlined" hide-details v-model="form.data.roles" />

                        <v-select clearable chips label="Wybierz oddziały" :items="company_branches" multiple
                            item-title="name" item-value="id" variant="outlined" hide-details
                            v-model="form.data.company_branches" />

                        <v-checkbox label="Wyślij informację z danymi logowania do rekrutera"
                            v-model="form.data.send_email" />

                    </v-card-text>
                    <v-card-actions>
                        <v-btn variant="outlined" color="#16a34a" size="large" @click="submit()"
                            :disabled="form.processing">
                            <i class="fa-solid fa-plus tw-mr-2"></i>
                            Dodaj użytkownika
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>
