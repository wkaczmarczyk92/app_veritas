<script setup>

import Modal from './Modal.vue';
import PointsHistory from './PointsHistory.vue';
import Loader from '@/Components/Loader.vue';

</script>

<template>
    <teleport to='body'>
        <Modal>
            <Suspense>
                <PointsHistory #default>
                </PointsHistory>
                <template #fallback>
                    <Loader class="tw-grow"></Loader>
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>
