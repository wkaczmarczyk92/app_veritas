<script setup>

import { a1_completed, a1_no_info, a1_progress } from './Composables/DisplayConditions';

const props = defineProps({
    caretaker: Object
});

</script>

<template>
    <v-window-item value="three">
        <div v-if="a1_no_info(caretaker)">
            <v-alert color="info" icon="$info" :text="`Brak informacji o A1. Prosimy sprawdzić za jakiś czas.`" />
        </div>
        <div v-else>
            <!-- <div v-if="a1_receive_date(caretaker)">
                <v-alert color="info" icon="$info" text="Aby wysłać wniosek o A1, musimy z powrotem otrzymać umowę" />
            </div> -->
            <div v-if="a1_progress(caretaker)">
                <v-alert color="info" icon="$info" class="!tw-py-8"
                    :text="`Prosimy o cierpliwośc, wniosek został złożony.`" />
            </div>
            <div v-else-if="a1_completed(caretaker)">
                <v-alert color="success" icon="$success" class="!tw-py-8">
                    <template v-slot:text>
                        <div class="tw-text-xl">
                            A1 aktualna od <span>{{
                                caretaker.assignments[0].contract.a1.one_start_date
                            }}</span> do
                            <span>{{ caretaker.assignments[0].contract.a1.one_end_date
                                }}</span>
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