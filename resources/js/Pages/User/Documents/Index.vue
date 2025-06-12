<script setup>

import UserLayout from '@/Layouts/UserLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import Contract from '@/Pages/User/Documents/Contract.vue'
import A1 from '@/Pages/User/Documents/A1.vue'
import EKUZ from '@/Pages/User/Documents/EKUZ.vue'
import RegistrationStatus from '@/Pages/User/Documents/RegistrationStatus.vue'

import { is_unregistered, has_business } from './Composables/DisplayConditions'

const props = defineProps({
    caretaker: Object
});

console.log('caretaker', props.caretaker)

const tab = ref(null)

</script>

<template>

    <Head title="VeritasApp - strona główna" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <div v-if="!caretaker || !caretaker.assignments[0]">
                    <v-alert color="error" icon="$error" class="!tw-py-8"
                        :text="`Wystąpił błąd podczas pobierania informacji o Twoich dokumentach. Skontaktuj się ze swoim rekruterem w celu wyjaśnienia sprawy.`" />
                </div>
                <!-- <div v-else-if="is_unregistered(caretaker)">
                    <v-alert color="error" icon="$error" class="!tw-py-8" :text="`Jesteś wyrejestrowany/a.`" />
                </div> -->
                <section v-else class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg">
                    <v-tabs v-model="tab" bg-color="primary">
                        <v-tab value="one">Umowa</v-tab>
                        <!-- <v-tab value="two">Aneks</v-tab> -->
                        <v-tab v-if="!has_business(caretaker)" value="three">A1</v-tab>
                        <v-tab v-if="!has_business(caretaker)" value="four">EKUZ</v-tab>
                        <!-- <v-tab value="five">Status zarejestrowania</v-tab> -->
                    </v-tabs>

                    <v-card-text>
                        <v-window v-model="tab">

                            <Contract :caretaker="caretaker" />
                            <A1 :caretaker="caretaker" />
                            <EKUZ :caretaker="caretaker" />
                            <!-- <RegistrationStatus :caretaker="caretaker" /> -->

                        </v-window>
                    </v-card-text>
                </section>
            </div>
        </div>
    </UserLayout>
</template>

<style>
.v-timeline-divider__inner-dot {
    position: relative;
}
</style>