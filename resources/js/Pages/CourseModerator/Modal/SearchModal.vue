<template>
    <div class="tw-w-full tw-flex tw-flex-col tw-my-5 tw-z-10">
        <div class="tw-flex tw-flex-col tw-text-start">
            <v-text-field clearable label="Szukaj" variant="outlined" class="" autofocus @change="submit()"
                @keyup.enter="submit()" v-model="search">
            </v-text-field>
            <v-divider :thickness="2" class="tw-border-gray-900 tw-my-2"></v-divider>

            <div class="tw-relative tw-py-8">
                <Processing v-if="search_processing" msg="Szukam..." />
                <div v-else-if="list != null">
                    <AlertInfo v-if="empty_list()">Brak trafień</AlertInfo>
                    <v-card v-if="list && list.lessons.length > 0" class="tw-mb-4 !tw-shadow-none">
                        <template v-slot:title>
                            <h2 class="tw-mb-1">Lekcje</h2>
                        </template>
                        <v-card-text class="tw-flex tw-flex-col tw-gap-2">
                            <SearchItem v-for="(lesson, index) in  list.lessons " :item="lesson"
                                :url="lesson_url(lesson)"
                                :subtitle="lesson.lessonable.type.name_pl + ' - ' + lesson.lessonable.name.slice(0, 30) + '...'"
                                :visibility="lesson.visibility_id" />
                        </v-card-text>
                    </v-card>
                    <v-card v-if="list && list.compendiums.length > 0" class="tw-mb-4 !tw-shadow-none">
                        <template v-slot:title>
                            <h2 class="tw-mb-1">Kompendia</h2>
                        </template>
                        <v-card-text class="tw-flex tw-flex-col tw-gap-2">
                            <SearchItem v-for="( compendium, index ) in  list.compendiums " :item="compendium"
                                :url="route('course_moderator.compendium.show', { id: compendium.id })"
                                :visibility="compendium.visibility_id" />
                        </v-card-text>
                    </v-card>
                    <v-card v-if="list && list.vademecums.length > 0" class="tw-mb-4 !tw-shadow-none">
                        <template v-slot:title>
                            <h2 class="tw-mb-1">Vademeca</h2>
                        </template>
                        <v-card-text class="tw-flex tw-flex-col tw-gap-2">
                            <SearchItem v-for="( vademecum, index ) in  list.vademecums " :item="vademecum"
                                :url="route('course_moderator.vademecum.show', { id: vademecum.id })"
                                :visibility="vademecum.visibility_id" />
                        </v-card-text>
                    </v-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref, watch } from 'vue'
import Processing from '@/Composables/Processing.vue';

import AlertInfo from '@/Components/Functions/AlertInfo.vue'

import SearchItem from '@/Composables/SearchItem.vue'

const search = ref('')
const search_processing = ref(false)

watch(search, (value) => {
    if (value == '' || value == null) {
        list.value = null
    }
})

const list = ref(null)

const submit = async () => {
    search_processing.value = true

    if (search.value.length <= 0) {
        search_processing.value = false
        list.value = null
        return
    }

    let response = await axios.post(route('course.search'), { query: search.value })
    response = response.data

    console.log(response)

    list.value = response
    console.log(list.value)

    search_processing.value = false
}

const empty_list = () => {
    return list.value.lessons.length <= 0 && list.value.compendiums.length <= 0 && list.value.vademecums.length <= 0
}

const lesson_url = (lesson) => {
    if (lesson.lessonable.compendium_type_id == 1) {
        return route('course_moderator.compendium.lesson.show', {
            compendium_id: lesson.lessonable.id,
            lesson_id: lesson.id
        })
    } else {
        return route('course_moderator.vademecum.lesson.show', {
            vademecum_id: lesson.lessonable.id,
            lesson_id: lesson.id
        })
    }
}

</script>

<style>
dialog:before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    /* background-image: url('/uploads/modal.project.create.form.bg.png'); */
    background-size: cover;
    background-position: center;
    opacity: .2;
}
</style>
