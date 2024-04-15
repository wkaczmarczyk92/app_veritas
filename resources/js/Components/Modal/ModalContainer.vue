<template>
    <teleport to="body">
        <div class="tw-backdrop tw-transition tw-duration-200 o-modal" v-for="(modal, index) in modal_store.active_modals"
            @click="modal_store.close(modal.name, index)" :key="`modal_${index}`">
            <dialog :id="`modal_id_${index}`"
                class="tw-modal tw-transition tw-duration-200 tw-relative tw-min-h-[80vh] tw-max-h-[90vh] tw-overflow-auto"
                :class="modal?.dialog?.class" open @click.stop>
                <div class="tw-flex tw-flex-row tw-gap-4">
                    <component :is="modal.component" />
                </div>
                <!-- <div class="tw-flex tw-flex-row tw-gap-2 tw-justify-end tw-mt-20">
                    <Btn @click="modal_store.close(modal.name, index)">Zamknij</Btn>
                </div> -->
            </dialog>
        </div>
    </teleport>
</template>

<script setup>

import { useModalStore } from '@/Pinia/ModalV2Store'
// import Btn from '@/Components/Form/Btn.vue'

const modal_store = useModalStore()

</script>

<style>
.tw-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.tw-modal {
    background-color: white;
    width: 600px;
    max-width: 100%;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
}

.hide-modal {
    opacity: 0;
}

.o-modal {
    /* background-color: red !important; */
    animation-name: example;
    animation-duration: 200ms;
    animation-timing-function: ease-in;
}

@keyframes example {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}
</style>
