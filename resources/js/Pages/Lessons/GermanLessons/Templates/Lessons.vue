<script setup>

import { router } from '@inertiajs/vue3'

const props = defineProps({
    german_lesson: {
        type: Object,
        required: true
    },
    admin_view: {
        type: Boolean,
        default: false
    }
})

defineEmits([
    'destroy'
])

const set_grid = () => {
    return props.admin_view ? 'lg:tw-grid-cols-4' : 'lg:tw-grid-cols-2'
}

const set_route = (lesson_id) => {
    return props.admin_view
        ? route('german.lessons.show', { id: lesson_id })
        : route('user.german.lessons.show', { id: lesson_id })
}

</script>

<template>
    <div class="tw-grid tw-grid-cols-1 tw-gap-4" :class="set_grid()" v-if="german_lesson.lessons.length > 0">
        <v-card v-for="(lesson, index) in german_lesson.lessons" :key="lesson.id"
            :title="(index + 1) + '. ' + lesson.name" @click="!admin_view ? router.get(set_route(lesson.id)) : null">
            <template v-slot:title>
                <div class="tw-flex tw-flex-row tw-justify-between tw-items-center">
                    <div class="tw-break-all">
                        {{ index + 1 }}. {{ lesson.name }}
                    </div>
                    <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">

                        <i class="fas fa-circle tw-text-base" v-if="admin_view"
                            :class="lesson.visibility.id == 1 ? 'tw-text-[#ea580c]' : 'tw-text-[#16a34a]'">
                            <v-tooltip activator="parent" location="top">{{ lesson.visibility.name_pl
                                }}</v-tooltip>
                        </i>
                        <v-menu v-if="admin_view">
                            <template v-slot:activator="{ props }">
                                <v-btn v-bind="props" icon variant="text">
                                    <i
                                        class="fas fa-ellipsis-v tw-text-2xl hover:tw-cursor-pointer hover:tw-text-blue-600"></i>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item :href="set_route(lesson.id)">
                                    <v-list-item-title>Szczegóły</v-list-item-title>
                                </v-list-item>
                                <!-- <v-list-item :href="route('german.lessons.edit', { id: lesson.id })">
                                                    <v-list-item-title>Edytuj</v-list-item-title>
                                                </v-list-item> -->
                                <v-list-item @click="$emit('destroy', lesson.id)" v-if="admin_view">
                                    <v-list-item-title>Usuń</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </template>
            <v-card-text>

            </v-card-text>
            <v-card-actions v-if="admin_view">
                <div class="tw-flex tw-flex-row tw-justify-end tw-w-full">
                    <div class="tw-text-sm tw-italic">
                        Dodane przez: {{ lesson.user.email }}
                    </div>
                </div>
            </v-card-actions>
        </v-card>
    </div>
    <v-alert v-else color="info">Brak lekcji do wyświetlenia.</v-alert>
</template>