<script setup>

import Modal from './Modal.vue';
import ReadyToDepartureForm from './ReadyToDepartureForm.vue';

import Loader from '@/Components/Loader.vue';

</script>

<template>
    <teleport to='body'>
        <Modal>
            <Suspense>
                <ReadyToDepartureForm #default />
                <template #fallback>
                    <Loader class="tw-grow"></Loader>
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>
