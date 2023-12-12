<script setup>

import Modal from './Modal.vue';
import ContactForm from './ContactForm.vue';

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
                <ContactForm @close="close"></ContactForm>
            </Modal>
        </Suspense>
    </teleport>
</template>