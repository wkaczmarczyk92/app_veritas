<script setup>

import Modal from './Modal.vue';
import UserFamilyRecommendations from './UserFamilyRecommendations.vue';

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
                <UserFamilyRecommendations 
                    :user="user"
                    @close="close">
                </UserFamilyRecommendations>
            </Modal>
        </Suspense>
    </teleport>
</template>