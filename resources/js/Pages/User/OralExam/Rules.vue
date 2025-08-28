<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Lessons from '@/Pages/Lessons/GermanLessons/Templates/Lessons.vue'

const props = defineProps({

})



</script>
<template>

    <Head title="VeritasApp - egzamin ustny - zasady'" />
    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-2 tw-mx-auto tw-max-w-8xl md:tw-max-w-5xl sm:tw-px-6 lg:tw-px-8 ">
                <v-card class="!tw-py-8">
                    <div class="tw-flex tw-flex-col tw-gap-1 tw-font-bold tw-items-center">
                        <div class="tw-text-red-500 tw-text-base md:tw-text-xl">
                            Egzamin ustny - zasady
                        </div>
                    </div>
                    <v-card-text>
                        <div class="tw-flex tw-flex-col tw-mt-4 tw-gap-4">
                            <div>
                                Po pozytywnym zaliczeniu testu online pojawi się możliwość umówienia się na ostatni etap
                                –
                                <span class="tw-font-bold">egzamin ustny</span>. System prosi Cię wówczas o wybranie
                                terminu
                                i godziny, aby nasz pracownik
                                (egzaminator) mógł do Ciebie zadzwonić w wybrany dzień o wybranej porze.
                            </div>
                            <div>
                                Egzamin ustny składa się z <span class="tw-font-bold">5 pytań z zakresu materiału
                                    kursowego</span> i trwa do 15 minut. Do
                                zaliczenia wymagane jest <span class="tw-font-bold">minimum 4 poprawne
                                    odpowiedzi</span>.
                                W przypadku niepowodzenia na egzaminie ustnym istnieje możliwość natychmiastowego
                                umówienia
                                się na egzamin ustny poprawkowy z osobą egzaminującą.
                            </div>
                            <div>
                                Pozytywnie zaliczony egzamin ustny oznacza nadanie statusu MITTEL, który w naszym
                                systemie
                                potwierdza komunikatywny poziom języka.
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </UserLayout>
</template>