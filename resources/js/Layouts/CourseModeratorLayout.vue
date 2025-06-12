<script setup>
import Navbar from '@/Pages/CourseModerator/Navigation/Navbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';

import ModalContainer from '@/Components/Modal/ModalContainer.vue';
import SearchModal from '@/Pages/CourseModerator/Modal/SearchModal.vue';

import { useModalStore } from '@/Pinia/ModalV2Store';
import { ref, onBeforeUnmount, onMounted } from 'vue'

const modal_store = useModalStore()

const ctrl_pressed = ref(false)

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown)
    window.addEventListener('keyup', handleKeyUp)
})

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown)
    window.removeEventListener('keyup', handleKeyUp)
})

const handleKeyDown = (event) => {
    if (ctrl_pressed.value == true && event.key === 'k') {
        event.preventDefault()

        if (modal_store.active('search_model')) {
            modal_store.close('search_model')
        } else {
            modal_store.add({
                name: 'search_model',
                component: SearchModal,
            })
        }
    }

    // Sprawdź, czy naciśnięty klawisz to Ctrl
    if (event.key === "Control") {
        console.log('CONTROL PRESSED')
        ctrl_pressed.value = true;
    }

    if (event.key === 'Escape' && modal_store.active('search_model')) {
        modal_store.close('search_model')
    }
}
const handleKeyUp = (event) => {
    if (event.key === "Control") {
        // console.log('CONTROL RELEASED')
        ctrl_pressed.value = false;
    }
}

</script>

<template>
    <div class="tw-relative">
        <AlertWrapper />
        <div class="tw-min-h-screen ">
            <Suspense>
                <template #default>
                    <Navbar #default />
                </template>
                <template #fallback>
                    <nav class="tw-bg-gray-800 tw-border-b tw-border-gray-100 tw-min-h-[100px]">
                        <div class="tw-p-4 tw-mx-auto tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                        </div>
                    </nav>
                </template>
            </Suspense>
            <!-- Page Heading -->
            <header class="" v-if="$slots.header">
                <div
                    class="tw-px-4 tw-py-1 tw-mx-auto tw-text-sm tw-text-gray-800 tw-max-w-8xl sm:tw-px-6 lg:tw-px-8 tw-flex tw-flex-row tw-justify-between">
                    <slot name="header" />

                    <div class="tw-my-auto">
                        <v-btn size="large" @click="modal_store.add({
                            name: 'search_model',
                            component: SearchModal,
                        })">
                            <i class="fa-regular fa-magnifying-glass tw-mr-2"></i>
                            Szukaj
                            <v-btn size="small" variant="outlined" class="tw-ml-3 !tw-opacity-100" disabled="disabled">
                                CTRL + K
                            </v-btn>
                        </v-btn>
                        <v-btn class="tw-ml-2" size="large" @click="modal_store.add({
                            name: 'search_model',
                            component: SearchModal,
                        })">
                            <i class="fa-regular fa-magnifying-glass tw-mr-2"></i>
                            Szukaj
                            <v-btn size="small" variant="outlined" class="tw-ml-3 !tw-opacity-100" disabled="disabled">
                                CTRL + K
                            </v-btn>
                        </v-btn>
                    </div>
                </div>
                <v-divider :thickness="4" class="border-opacity-75" color="info"></v-divider>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
        <Suspense>
            <ModalContainer />
        </Suspense>
    </div>
</template>
