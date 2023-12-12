<script setup>

import Modal from './Modal.vue';
import UserCaretakerRecommendations from './UserCaretakerRecommendations.vue';

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
])

const close = () => emit('update:model_value', false);

</script>

<template>
    <teleport to='body'>
        <Suspense>
            <Modal>
                <UserCaretakerRecommendations 
                    :user="user"
                    @close="close">
                </UserCaretakerRecommendations>
            </Modal>
        </Suspense>
    </teleport>
</template>