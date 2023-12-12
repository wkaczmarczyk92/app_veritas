<script setup>

import Modal from './Modal.vue';
import ReadyToDepartureForm from './ReadyToDepartureForm.vue';

defineProps({
    model_value: {
        type: Boolean,
        required: true
    },
    ready_to_departure_dates: {
        type: [Object, null],
        required: true
    }
});

const emit = defineEmits([
    'update:model_value',
    'update-ready-to-departure-date'
])

const close = () => emit('update:model_value', false);

</script>

<template>
    <teleport to='body'>
        <Suspense>
            <Modal>
                <ReadyToDepartureForm 
                    :ready_to_departure_dates="ready_to_departure_dates"
                    @update="$emit('update-ready-to-departure-date', $event)"
                    @close="close"></ReadyToDepartureForm>
            </Modal>
        </Suspense>
    </teleport>
</template>