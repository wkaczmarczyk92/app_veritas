<script setup>

import { registration, is_registered, is_unregistered } from './Composables/DisplayConditions';

const props = defineProps({
    caretaker: Object
})

</script>

<template>
    <v-window-item value="five">
        <div v-if="!registration(caretaker)">
            <v-alert color="info" icon="$info"
                text="Brak danych na temat statusu rejestracji. Skontaktu się ze swoim rekruterem." />
        </div>
        <div v-else>
            <div v-if="is_registered(caretaker)">
                <v-alert color="success" icon="$success" text="Jesteś zarejestrowany/a." class="!tw-py-8">
                    <template v-slot:text>
                        <div class="tw-text-xl">
                            Jesteś zarejestrowany/a.
                        </div>
                    </template>
                </v-alert>
            </div>
            <div v-else-if="is_unregistered(caretaker)">
                <v-alert color="info" icon="$info"
                    :text="`Jesteś wyrejestrowana. Data wyrejestrowania - ${caretaker.assignments[0].contract.registration.reg_unregistered_date}`" />
            </div>
            <div v-else>
                <v-alert color="error" icon="$error" text="Brak informacji o statusie zarejestrowania." />
            </div>
        </div>
    </v-window-item>
</template>