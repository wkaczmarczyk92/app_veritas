<script setup>

import { router } from '@inertiajs/vue3'

import ExpandedButton from '@/Pages/User/Homepage/Parts/ExpandedButton.vue';
import { useModalStore } from '@/Pinia/ModalStore';
import { useUserStore } from '@/Pinia/UserStore'

const props = defineProps({
    no_crm_account: {
        type: Boolean,
        default: false
    }
})

const modalStore = useModalStore();
const userStore = useUserStore();

</script>

<template>
    <teleport to='body'>
        <div class="floating-icon-container tw-z-30">
            <div class="floating-icon tw-flex tw-flex-col md:tw-flex-row tw-gap-2">
                <div class="tw-text-right" v-if="!no_crm_account">
                    <ExpandedButton icon="bok-icon-custom" value="Wyślij zgłoszenie" type="submit"
                        add_class="expanded-button-xxx bok-icon tw-bg-gradient-to-tr tw-from-red-500 tw-to-red-800 hover:tw-from-red-600 hover:tw-to-red-900 tw-shadow-xl tw-border tw-border-solid tw-border-red-400"
                        @click="modalStore.visibility.bok = true">
                    </ExpandedButton>
                </div>
                <div class="tw-text-right" v-if="!no_crm_account">
                    <ExpandedButton icon="fa-solid fa-calendar-days" value="Zgłoś chęć wyjazdu"
                        add_class="expanded-button-172 calendar-icon tw-bg-gradient-to-tr tw-from-green-500 tw-to-green-800 hover:tw-from-green-600 hover:tw-to-green-900 tw-shadow-xl tw-border tw-border-solid tw-border-green-400"
                        @click="modalStore.visibility.ready_to_departure = true">
                    </ExpandedButton>
                </div>
                <div class="tw-text-right" v-if="!no_crm_account">
                    <ExpandedButton icon="fa-solid fa-comments" value="Skontaktuj się"
                        add_class="expanded-button-137 contact-icon tw-bg-gradient-to-tr tw-from-blue-500 tw-to-blue-800 hover:tw-from-blue-600 hover:tw-to-blue-900 tw-shadow-xl tw-border tw-border-solid tw-border-blue-400"
                        @click="modalStore.visibility.contact_form = true">
                    </ExpandedButton>
                </div>
                <div class="tw-text-right" v-if="no_crm_account">
                    <a href="tel:+48717588140">
                        <ExpandedButton icon="fa-solid fa-phone" value="Zadzwoń!"
                            add_class="expanded-button-137 contact-icon tw-bg-gradient-to-tr tw-from-blue-500 tw-to-blue-800 hover:tw-from-blue-600 hover:tw-to-blue-900 tw-shadow-xl tw-border tw-border-solid tw-border-blue-400">
                        </ExpandedButton>
                    </a>
                </div>
                <div class="tw-text-right">
                    <ExpandedButton icon="fa-sharp fa-regular fa-right-from-bracket" value="Wyloguj się" type="submit"
                        add_class="expanded-button-116 contact-icon tw-bg-gradient-to-tr tw-from-gray-500 tw-to-gray-800 hover:tw-from-gray-600 hover:tw-to-gray-900 tw-shadow-xl tw-border tw-border-solid tw-border-gray-400"
                        @click="userStore.logout(); router.post(route('logout'))">
                    </ExpandedButton>
                </div>
            </div>
        </div>
    </teleport>
</template>
