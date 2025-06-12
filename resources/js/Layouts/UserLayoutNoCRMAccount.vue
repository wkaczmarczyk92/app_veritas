<script setup>
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';

import FloatingUserMenu from '@/Components/Templates/FloatingUserMenu.vue';
import Modals from '@/Modals/Modals.vue';
// import Modal from '@/Modals/Modal.vue';
import ModalAlert from '@/Composables/ModalAlert.vue';

import { onBeforeMount, ref } from 'vue';
import UserNavbarNoCRMAccount from '@/Components/Navigation/UserNavbarNoCRMAccount.vue';


// const alert_info = async () => {
//     let response = await axios.get(route('crm.document.check'))
//     response = response.data

//     console.log(response)

//     if (response.success) {
//         alert_modal.value = true
//         alert_msg.value = response.msg
//     }
// }

const alert_modal = ref(false)
// const alert_msg = ref('')

// onBeforeMount(async () => {
//     await alert_info()

// })

</script>

<template>
    <teleport to="body" v-if="alert_modal">
        <!-- <ModalAlert type="info" @close="alert_modal = false" :with_cookies="false">
            {{ alert_msg }}
        </ModalAlert> -->
    </teleport>
    <div class="tw-relative">
        <AlertWrapper />
        <div class="tw-min-h-screen tw-bg-gray-100">
            <!-- <div class="tw-min-h-screen tw-bg-gradient-to-br tw-from-gray-400 tw-to-gray-700"> -->
            <UserNavbarNoCRMAccount />
            <main class="tw-background tw-relative">
                <slot />
            </main>
            <Suspense>
                <FloatingUserMenu :no_crm_account="true" />
            </Suspense>

            <Suspense>
                <Modals />
            </Suspense>
        </div>
    </div>
</template>
