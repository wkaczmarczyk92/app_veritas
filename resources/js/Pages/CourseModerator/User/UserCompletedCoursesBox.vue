<template>
    <v-card :loading="false" title="Ukończone kursy i szkolenia" subtitle="4/47" class="!tw-p-4">
        <template v-slot:prepend>
            <i class="fa-regular fa-list-check tw-text-2xl"></i>
        </template>
        <v-card-text class="tw-flex tw-gap-1 tw-flex-col tw-my-6">
            <!-- <AlertInfo>Brak ukończonych kursów i szkoleń</AlertInfo> -->

            <v-infinite-scroll :height="300" :items="items" @load="load">
                <template v-for="(item, index) in items" :key="item">
                    <div :class="['tw-p-2', index % 2 === 0 ? 'tw-bg-gray-200' : '']">
                        <div class="tw-grid tw-grid-cols-4">
                            <div class="tw-col-span-3">
                                <div class="tw-flex tw-flex-col">
                                    <div>
                                        {{ item.name }}
                                    </div>
                                    <div class="tw-ms-4">
                                        nazwa lekcji
                                    </div>
                                </div>
                            </div>
                            <div>
                                2024-03-13
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:empty class="test">
                    <AlertInfo class="tw-w-full">Brak większej ilości ukończonych kursów!</AlertInfo>
                </template>
            </v-infinite-scroll>

        </v-card-text>
        <v-card-actions>
            <!-- <v-btn variant="tonal" color="#0284c7" size="small" @click="" :disabled="false">
                <i class="fa-solid fa-user-pen tw-mr-2"></i>
                Edytuj
            </v-btn> -->
        </v-card-actions>
    </v-card>
</template>

<script setup>

import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import { ref } from 'vue'

const items = ref([
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
        lesson: 'jakas lekcja'
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
    {
        name: 'Kompendium wiedzy – dział rekrutacji (biura regionalne)',
    },
])

// const load = ({ done }) => {
//     done('empty')
// }

const load = async ({ done }) => {
    // Symulacja wywołania API
    // await new Promise(resolve => setTimeout(resolve, 1000)); // Czekaj 1 sekundę
    // const newItems = [{ name: 'Nowy element' }]; // Nowe elementy do dodania
    // items.value.push(...newItems);

    setTimeout(() => {
        done('empty');
    }, 2000);

};

</script>