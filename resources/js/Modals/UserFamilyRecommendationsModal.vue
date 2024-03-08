<script setup>

import Modal from './Modal.vue';
import UserFamilyRecommendations from './UserFamilyRecommendations.vue';

import Loader from '@/Components/Loader.vue';

</script>

<template>
    <teleport to='body'>
        <Modal>
            <Suspense>
                <UserFamilyRecommendations #default />
                <template #fallback>
                    <Loader class="tw-grow"></Loader>
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>
