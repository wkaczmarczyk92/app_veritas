<script setup>

import Modal from './Modal.vue';
import UserCaretakerRecommendations from './UserCaretakerRecommendations.vue';

import Loader from '@/Components/Loader.vue';

</script>

<template>
    <teleport to='body'>
        <Modal>
            <Suspense>
                <UserCaretakerRecommendations #default />
                <template #fallback>
                    <Loader #fallback class="tw-grow"></Loader>
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>
