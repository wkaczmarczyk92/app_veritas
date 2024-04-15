<template>
    <v-dialog max-width="full" fullscreen>
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" color="#0284c7" text="Podgląd" variant="outlined" :disabled="progress">
                <template v-slot:prepend>
                    <i class="fa-sharp fa-light fa-telescope"></i>
                </template>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Podgląd kompendium">
                <template v-slot:prepend>
                    <i class="fa-sharp fa-light fa-telescope tw-text-2xl"></i>
                </template>
                <v-card-text class="">
                    <div class="tw-grid tw-grid-cols-12 tw-justify-center tw-max-w-7xl tw-gap-4 tw-mx-auto tw-relative">
                        <v-card :loading="false" :title="form.name ? form.name : 'brak nazwy kompendium'"
                            :subtitle="'Dodano - ' + format(new Date())" class="!tw-p-4 tw-col-span-9">
                            <v-card-text class="tw-mt-4 !tw-text-lg">
                                <div v-if="form.description" v-html="form.description"></div>
                                <div v-else>brak opisu kompendium</div>
                            </v-card-text>
                        </v-card>
                        <v-card :loading="false" class="!tw-p-4 !tw-fixed tw-w-3/12 tw-right-[8%]">
                            <v-card-text class="!tw-text-lg">
                                <v-list density="compact">
                                    <v-list-subheader>Kurs zawiera</v-list-subheader>
                                    <!--
                                    <v-list-item color="primary">
                                        <template v-slot:prepend>
                                            <i class="fa-light fa-glasses tw-mr-2"></i>
                                        </template>

<v-list-item-title v-text="'5:30 min'"></v-list-item-title>
</v-list-item> -->

                                    <v-list-item color="primary">
                                        <template v-slot:prepend>
                                            <i class="fa-regular fa-book tw-mr-2"></i>
                                        </template>

                                        <v-list-item-title v-text="'41 Tematów'"></v-list-item-title>
                                    </v-list-item>

                                    <v-list-item color="primary">
                                        <template v-slot:prepend>
                                            <i class="fa-regular fa-circle-info tw-mr-2"></i>
                                        </template>

                                        <v-list-item-title v-text="'12 Testów'"></v-list-item-title>
                                    </v-list-item>

                                    <!-- <v-list-item color="primary">
                                        <template v-slot:prepend>
                                            <i class="fa-sharp fa-light fa-file-certificate tw-mr-2"></i>
                                        </template>

                                        <v-list-item-title v-text="'Certyfikat'"></v-list-item-title>
                                    </v-list-item> -->
                                </v-list>
                            </v-card-text>
                        </v-card>
                    </div>
                </v-card-text>
                <v-card-actions class="tw-fixed tw-bottom-0 tw-right-[2%]">
                    <v-spacer></v-spacer>

                    <v-btn text="Zamknij podgląd" @click="isActive.value = false"></v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>

<script setup>

import { format } from '@/Components/Functions/DateFormat.vue';

const props = defineProps({
    progress: {
        type: Boolean,
    },
    form: {
        type: Object,
        required: true
    }
})

console.log(props.progress)

</script>
