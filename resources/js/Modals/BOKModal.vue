<script setup>

import Modal from './Modal.vue';
import BOKForm from './BOKForm.vue';

defineProps({
    model_value: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits([
    'update:model_value'
])

const close = () => emit('update:model_value', false);

</script>

<template>
    <teleport to='body'>
        <Suspense>
            <Modal>
                <BOKForm @close="close"></BOKForm>
            </Modal>
        </Suspense>
    </teleport>
</template>