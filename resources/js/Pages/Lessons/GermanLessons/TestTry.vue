<script setup>

import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import Spinner from '@/Components/Forms/Spinner.vue'
import { useTestStore } from '@/Pinia/TestStore'
import TestContentPreview from '@/Pages/Lessons/GermanLessons/TestContentPreview.vue'

const props = defineProps({
    lesson: {
        type: Object,
        required: true
    },
    is_admin: {
        type: Boolean,
        default: false
    }
})

const test_store = useTestStore()
const dialog = ref(false)

watch(dialog, (value) => {
    if (!value) {
        console.log('Dialog closed')
        test_store.reset()
    }
})

</script>

<template>
    <v-dialog max-width="800" v-model="dialog">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" color="surface-variant" text="Rozwiąż test" variant="outlined"></v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <TestContentPreview :test_name="props.lesson.test[0].name" :test_id="props.lesson.test[0].id"
                :questions="props.lesson.test[0].questions" :is_admin="is_admin"
                @close-dialog="isActive.value = false" />
        </template>
    </v-dialog>
</template>