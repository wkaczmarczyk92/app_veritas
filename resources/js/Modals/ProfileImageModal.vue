<script setup>

import Modal from './Modal.vue';
import UploadProfileImage from './UploadProfileImage.vue';

</script>

<template>
    <teleport to='body'>
        <Suspense>
            <Modal>
                <UploadProfileImage />
            </Modal>
        </Suspense>
    </teleport>
</template>
