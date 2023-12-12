<script setup>

import { router } from '@inertiajs/vue3'

import ExpandedButton from '@/Pages/Homepage/Parts/ExpandedButton.vue';

defineEmits([
    'toggle'
])

</script>

<template>
    <teleport to='body' >
        <div class="floating-icon-container z-30">
            <div class="floating-icon flex flex-col md:flex-row gap-2">
                <div class="text-right">
                    <ExpandedButton
                        icon="bok-icon-custom"
                        value="Wyślij zgłoszenie"
                        type="submit"
                        add_class="expanded-button-xxx bok-icon bg-gradient-to-tr from-red-500 to-red-800 hover:from-red-600 hover:to-red-900 shadow-xl border border-solid border-red-400"
                        @click="$emit('toggle', 'bok')">
                    </ExpandedButton>
                </div>
                <div class="text-right">
                    <ExpandedButton
                        icon="fa-solid fa-calendar-days"
                        value="Zgłoś chęć wyjazdu"
                        add_class="expanded-button-172 calendar-icon bg-gradient-to-tr from-green-500 to-green-800 hover:from-green-600 hover:to-green-900 shadow-xl border border-solid border-green-400"
                        @click="$emit('toggle', 'ready_to_departure')">
                    </ExpandedButton>
                </div>                
                <div class="text-right">
                    <ExpandedButton
                        icon="fa-solid fa-comments"
                        value="Skontaktuj się"
                        add_class="expanded-button-137 contact-icon bg-gradient-to-tr from-blue-500 to-blue-800 hover:from-blue-600 hover:to-blue-900 shadow-xl border border-solid border-blue-400"
                        @click="$emit('toggle', 'contact_form')">
                    </ExpandedButton>
                </div>
                <div class="text-right">
                    <ExpandedButton
                        icon="fa-sharp fa-regular fa-right-from-bracket"
                        value="Wyloguj się"
                        type="submit"
                        add_class="expanded-button-116 contact-icon bg-gradient-to-tr from-gray-500 to-gray-800 hover:from-gray-600 hover:to-gray-900 shadow-xl border border-solid border-gray-400"
                        @click="router.post(route('logout'))">
                    </ExpandedButton>
                </div>
            </div>
        </div>
    </teleport>
</template>