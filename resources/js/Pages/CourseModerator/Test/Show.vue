<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

import TInput from '@/Composables/Form/TInput.vue'
import Editor from '@tinymce/tinymce-vue'
import InputError from '@/Components/InputError.vue'


import { format } from '@/Components/Functions/DateFormat.vue'

// import Preview from './Preview.vue'

import { upload_file } from '@/Composables/UploadFilesTinyMCE'

import MCEEditor from '@/Composables/MCEEditor.vue'

import Processing from '@/Composables/Processing.vue'

import CompanyBranchChip from '@/Composables/Chips/CompanyBranchChip.vue'
import RoleChip from '@/Composables/Chips/RoleChip.vue'
import LessonChip from '@/Composables/Chips/LessonChip.vue'
import VademecumChip from '@/Composables/Chips/VademecumChip.vue'
import CompendiumChip from '@/Composables/Chips/CompendiumChip.vue'

import FlexDataDisplay from '@/Composables/FlexDataDisplay.vue'
import VisibilityName from '@/Composables/VisibilityName.vue'

import QuestionDisplay from './QuestionDisplay.vue'

const props = defineProps({
    test: {
        type: Object,
        required: true
    }
})

console.log('test', props.test)

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Testy',
        disabled: false,
        href: route('course_moderator.test.index')
    },
    {
        title: props.test.name,
        disabled: true
    }
]

const lesson_url = () => {
    if (props.test.lesson.length > 0) {
        if (props.test.lesson[0].lessonable.compendium_type_id == 1) {
            return route('course_moderator.compendium.lesson.show', {
                compendium_id: props.test.lesson[0].lessonable.id,
                lesson_id: props.test.lesson[0].id
            })
        } else {
            return route('course_moderator.vademecum.lesson.show', {
                vademecum_id: props.test.lesson[0].lessonable.id,
                lesson_id: props.test.lesson[0].id
            })
        }
    }
}

const alert_store = AlertStore()
const processing = ref({
    show: false,
    text: 'Usuwanie testu...'
})

const delete_test = async () => {
    processing.value.text = 'Usuwanie testu...'
    processing.value.show = true
    if (confirm('Czy na pewno chcesz usunąć test?')) {
        let response = await axios.delete(route('course_moderator.test.destroy', { id: props.test.id }))
        response = response.data

        alert_store.pushAlert(response)

        if (response.success) {
            processing.value.text = 'Trwa przekierowywanie...'
            setTimeout(() => {
                router.visit(route('course_moderator.test.index'))
            }, 1500)
        } else {
            processing.value = false
        }
    } else {
        processing.value = false
    }

}

</script>


<template>

    <Head title="VeritasApp - moderator kursów - dodaj test" />
    <CourseModeratorLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1 tw-gap-4 tw-relative">
                <Processing v-if="processing.show" :msg="processing.text" />
                <v-card :loading="false" :title="test.name" class="!tw-p-4 ">
                    <template v-slot:prepend>
                        <i class="fa-light fa-graduation-cap tw-text-2xl"></i>
                    </template>
                    <!-- <template v-slot:title>
                        <span>asd</span>
                    </template> -->
                    <template v-slot:subtitle v-if="test.lesson.length > 0" class="!tw-opacity-100">
                        <LessonChip v-if="test.lesson.length > 0" :lesson_name="test.lesson[0].name"
                            :url="lesson_url()" />
                        <CompendiumChip v-if="test.lesson[0].lessonable.compendium_type_id == 1"
                            :url="route('course_moderator.compendium.show', { id: test.lesson[0].lessonable.id })"
                            :compendium_name="test.lesson[0].lessonable.name" class="tw-opacity-100 tw-z-10" />
                        <VademecumChip v-else :vademecum_name="test.lesson[0].lessonable.name"
                            :url="route('course_moderator.vademecum.show', { id: test.lesson[0].lessonable.id })"
                            class="tw-opacity-100 tw-z-10" />
                    </template>
                    <v-card-text class="tw-mt-10">
                        <FlexDataDisplay title="Limit czasu:" :value="test.time ? test.time + 'm' : 'brak'">
                            <template v-slot:icon>
                                <i class="fa-regular fa-timer"></i>
                            </template>
                        </FlexDataDisplay>
                        <FlexDataDisplay title="Widoczność:">
                            <template v-slot:icon>
                                <i class="fa-regular fa-eyes"></i>
                            </template>
                            <template v-slot:value>
                                <span :class="test.visibility.id == 1 ? 'tw-text-orange-600' : 'tw-text-green-600'">
                                    {{ test.visibility.name_pl }}
                                </span>
                            </template>
                        </FlexDataDisplay>

                        <h2 class="tw-mt-8">Test dostępny dla:</h2>
                        <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-flex-wrap tw-mt-2">
                            <RoleChip v-for="(item, index) in test.roles" :role="item" :key="index" />
                            <CompanyBranchChip v-for="(item, index) in test.company_branches" :company_branch="item"
                                :key="index" />
                        </div>

                    </v-card-text>
                    <v-card-actions class="tw-mt-10">
                        <div class="tw-flex tw-flex-row tw-justify-end tw-text-sm">
                            Utworzono: {{ format(test.created_at) }}
                        </div>
                        <div></div>
                    </v-card-actions>
                </v-card>
                <v-card :loading="false" title="Pytania" class="!tw-p-4">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-clipboard-question tw-text-2xl"></i>
                    </template>
                    <v-card-text>
                        <div class="tw-flex tw-flex-col tw-gap-4 tw-mt-4">
                            <QuestionDisplay :questions="test.questions" />
                        </div>
                    </v-card-text>
                </v-card>

            </div>
            <div class="tw-mt-4 tw-flex tw-flex-row tw-justify-end">
                <v-btn color="#dc2626" size="small" variant="tonal" :disabled="false" @click="delete_test()">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-trash"></i>
                    </template>
                    Usuń test
                </v-btn>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<style>
.autocomplete-input .v-field__input {
    padding-top: 0;
    padding-bottom: 0;
}

.v-list-item-title {
    white-space: unset !important;
}

.v-label {
    width: 100%;

}

.v-selection-control--disabled {
    opacity: 1;
}
</style>

<!-- [Verse]
Hej, tu Muskała, z Matim przy boku,
Gramy w ping-ponga, od świtu do zmroku.
W podziemiach miasta, gdzie światła się mrużą,
Tam nasze suki, blancika kurzą
Typy się patrzą na moją rakietę, 
zaraz im jebnę i wciągnę se fetę,
Na szybko potem wygram z frajerem,
Jebać takie kurwy - jestem fajterem

[Chorus]
W rytmie ping-ponga, my, Mati Muskała,
W podziemiu Ty kurwo urwę Ci jaja,
Szybkie wymiany, piłka już leci,
Nic nas nie zatrzyma, jebać was śmieci!

[Verse]
Teraz Mati przejmuje mikrofon,
a Twoja stara odbiera domofon, 
Napierdalam rakietą,
Jak Twój stary żonę, 
Pamiętaj - frytki zawsze mają być wysmażone 

... -->
<!-- 
Yo, słuchaj to historia Ostiny - gdzie stara gwardia działa bez maliny,
Na scenie bez przerwy, przez dnie i noce, ogarniając staruszkom ich spocone koce

Pierwsza zasada, dobra kadra to podstawa,
Stare baby w akcji, jak wojownicy z czasów dawna,
One nie boją się trudów i pracy,
Seta na start i do pracy rodacy

Veritas i Medipe niby są w grze,
Ale Ostina to wyróżnia się,
Mamy style, klasę i najlepsze opiekunki,
Konkurencja wysyłamy po nasze sprawunki,
W walce o seniorów, Ostina stoi na czele,
Na naszych wrogów puszczamy mele

Dziadki na wyjazdach, to już inna opowieść,
Trochę chleją, trochę ćpają, żeby życie dowieźć,
Ale opiekunki Ostiny zawsze czujne, zawsze blisko,
Pilnują, dbają, by starość była jak najlepsze disco.

... 

bu

To nie praca to misja - by dawać dziadkom radość,

By każdy dzień był lepszy, choć czasem trudny i pod górę,
Ostina Opieka, nie tylko firma – to rodzina,
Dla nich każda babcia, każdy dziadek to najważniejsza osoba na świecie, bez przeminy. -->