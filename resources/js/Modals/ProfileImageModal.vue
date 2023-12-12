<script setup>

import Modal from './Modal.vue';
import UploadProfileImage from './UploadProfileImage.vue';

defineProps({
    model_value: {
        type: Boolean,
        required: true
    },
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits([
    'update:model_value',
    'update'
])

const close = () => emit('update:model_value', false);

</script>

<template>
    <teleport to='body'>
        <Suspense>
            <Modal>
                <UploadProfileImage 
                    :user_id="user.id"
                    @update="$emit('update', $event)"
                    @close="close">
                </UploadProfileImage>
            </Modal>
        </Suspense>
    </teleport>
</template>