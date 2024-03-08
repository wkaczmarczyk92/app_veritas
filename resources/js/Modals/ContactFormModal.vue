<script setup>

import Modal from './Modal.vue';
import ContactForm from './ContactForm.vue';

import Loader from '@/Components/Loader.vue';

</script>

<template>
    <teleport to='body'>
        <Modal>
            <Suspense>
                <ContactForm #default />
                <template #fallback>
                    <Loader class="tw-grow" />
                </template>
            </Suspense>
        </Modal>
    </teleport>
</template>
