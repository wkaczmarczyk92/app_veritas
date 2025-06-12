<script setup>

import { ekuz_no_info, ekuz_progress, ekuz_completed } from './Composables/DisplayConditions';

const props = defineProps({
    caretaker: Object
})

</script>

<template>
    <v-window-item value="four">
        <div v-if="ekuz_no_info(caretaker)">
            <v-alert color="info" icon="$info" text="Brak informacji o karcie EKUZ. Prosimy sprawdzić za jakiś czas." />
        </div>
        <div v-else>
            <div v-if="ekuz_progress(caretaker)">
                <v-alert color="info" icon="$info"
                    text="Prosimy o cierpliwość, jak tylko otrzymamy kartę od NFZ wyślemy ją na adres gdzie będzie Pan/i obecnie przebywać." />
            </div>
            <div v-else-if="ekuz_completed(caretaker)">
                <v-alert color="success" icon="$success" class="!tw-py-8">
                    <template v-slot:text>
                        <div class="tw-text-xl">
                            Karta EKUZ aktualna od
                            <span>{{ caretaker.assignments[0].contract.a1.ekuz.ekz_start_date }}
                            </span> do <span>{{
                                caretaker.assignments[0].contract.a1.ekuz.ekz_end_date }}</span>
                        </div>
                    </template>
                </v-alert>
            </div>
            <div v-else>
                <v-alert color="error" icon="$error" class="!tw-py-8"
                    :text="`Wystąpił błąd podczas pobierania danych. Spróbuj ponownie później.`" />
            </div>
        </div>
    </v-window-item>
</template>