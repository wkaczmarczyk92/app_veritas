<template>
    <v-dialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn size="x-large" color="#ea580c" v-bind="activatorProps">
                Zasady testu
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Zasady testu online">
                <v-card-text>
                    <div class="tw-text-lg tw-flex tw-flex-col tw-gap-4">
                        <div>
                            Test online składa się z <span class="tw-font-bold">20 losowo wybranych pytań obejmujących
                                cały materiał</span> i jest
                            ograniczony czasowo - na jego rozwiązanie <span class="tw-font-bold">masz 10 minut</span>.
                        </div>
                        <div>
                            Aby go zaliczyć, musisz uzyskać co najmniej <span class="tw-font-bold">80% poprawnych
                                odpowiedzi</span>.
                            Jeśli nie zdasz to możesz podchodzić do tego testu jeszcze raz tego samego dnia. Czyli masz
                            dwie szanse dziennie.
                        </div>
                        <div>
                            Po pozytywnym zaliczeniu testu online pojawi się możliwość umówienia się na ostatni etap –
                            <span class="tw-font-bold">egzamin ustny</span>. System prosi Cię wówczas o wybranie terminu
                            i godziny, aby nasz pracownik
                            (egzaminator) mógł do Ciebie zadzwonić w wybrany dzień o wybranej porze.
                        </div>
                        <div>
                            Egzamin ustny składa się z <span class="tw-font-bold">5 pytań z zakresu materiału
                                kursowego</span> i trwa do 15 minut. Do
                            zaliczenia wymagane jest <span class="tw-font-bold">minimum 4 poprawne odpowiedzi</span>.
                            W przypadku niepowodzenia na egzaminie ustnym istnieje możliwość natychmiastowego umówienia
                            się na egzamin ustny poprawkowy z osobą egzaminującą.
                        </div>
                        <div>
                            Pozytywnie zaliczony egzamin ustny oznacza nadanie statusu MITTEL, który w naszym systemie
                            potwierdza komunikatywny poziom języka.
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn text="Zamknij" @click="isActive.value = false"></v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>