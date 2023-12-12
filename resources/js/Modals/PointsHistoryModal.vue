<script setup>

import Modal from './Modal.vue';
import PointsHistory from './PointsHistory.vue';
import Loader from '@/Components/Loader.vue';

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
        <Modal>
            <Suspense>
                <PointsHistory #default
                    :user="user"
                    @close="close">
                </PointsHistory>
                <template #fallback>
                    <Loader class="grow"></Loader>
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>