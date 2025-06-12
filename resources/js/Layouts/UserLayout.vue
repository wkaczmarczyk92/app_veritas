<script setup>
import UserNavbar from '@/Components/Navigation/UserNavbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';

import FloatingUserMenu from '@/Components/Templates/FloatingUserMenu.vue';
import Modals from '@/Modals/Modals.vue';
// import Modal from '@/Modals/Modal.vue';
import ModalAlert from '@/Composables/ModalAlert.vue';

import { onBeforeMount, ref } from 'vue';


const alert_info = async () => {
    let response = await axios.get(route('crm.document.check'))
    response = response.data

    console.log(response)

    if (response.success) {
        alert_modal.value = true
        alert_msg.value = response.msg
    }
}

const alert_modal = ref(false)
const alert_msg = ref('')

onBeforeMount(async () => {
    await alert_info()

})

const open_dialog = ref(true)

</script>

<template>
    <teleport to="body" v-if="alert_modal">
        <ModalAlert type="info" @close="alert_modal = false">
            {{ alert_msg }}
        </ModalAlert>
    </teleport>

    <div class="tw-relative">
        <AlertWrapper></AlertWrapper>
        <div class="tw-min-h-screen  tw-bg-gray-100">
            <UserNavbar></UserNavbar>
            <main class="tw-background tw-relative">
                <slot />
            </main>
            <Suspense>
                <FloatingUserMenu></FloatingUserMenu>
            </Suspense>

            <Suspense>
                <Modals></Modals>
            </Suspense>
        </div>
    </div>
</template>
