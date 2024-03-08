<script setup>

import Modal from './Modal.vue';
import BOKForm from './BOKForm.vue';

import Loader from '@/Components/Loader.vue';
</script>

<template>
    <teleport to='body'>
        <Modal>
            <Suspense>
                <BOKForm #default></BOKForm>
                <template #fallback>
                    <Loader class="tw-grow"></Loader>
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>
