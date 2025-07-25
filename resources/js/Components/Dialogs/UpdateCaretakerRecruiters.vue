<script setup>

import { use_processing_store } from '@/Pinia/ProcessingStore';
import { AlertStore } from '@/Pinia/AlertStore';
import { use_update_caretaker_recruiter_store } from '@/Pinia/Admin/UpdateCaretakerRecruiters';
import Spinner from '../Forms/Spinner.vue';

const update_caretaker_recruiter_store = use_update_caretaker_recruiter_store()
const processing_store = use_processing_store()

</script>

<template>
    <v-dialog max-width="700">
        <template v-slot:activator="{ props: activatorProps }">
            <v-list-item link v-bind="activatorProps">
                <v-list-item-title>Aktualizuj rekruterów</v-list-item-title>
            </v-list-item>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Aktualizacja rekruterów opiekunek">
                <v-card-text>
                    <Spinner v-if="processing_store.state" msg="Aktualizowanie rekruterów" />
                    <Transition mode="out-in" name="fade">
                        <div>Na pewno chcesz uruchomić aktualizację rekruterów przypisanych do opiekunek? Proces może potrwać nawet 10min! Podczas tego czasu NIE WOLNO wyłączać tego okna ani okna przeglądarki.</div>
                    </Transition>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text="Potwierdź" color="#2563eb" variant="outlined" @click="update_caretaker_recruiter_store.submit()"></v-btn>
                    <v-btn text="Anuluj" color="#dc2626" @click="isActive.value = false"></v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>