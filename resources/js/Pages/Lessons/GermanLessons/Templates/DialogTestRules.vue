<template>
    <v-dialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn size="x-large" color="#ea580c" v-bind="activatorProps">
                Zasady testu
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Zasady testu">
                <v-card-text>
                    <div class="tw-text-lg">
                        Test składa się z 20 losowo wybranych pytań obejmujących cały materiał i jest ograniczony
                        czasowo - na jego rozwiązanie masz 10 minut.<br><br>
                        Aby go zaliczyć, musisz uzyskać co najmniej <span class="tw-font-bold">80%</span> poprawnych odpowiedzi.<br><br>
                        Pozytywne zaliczenie testu daje Ci możliwość udziału w programie <span class="tw-font-bold">„Zostań Mittelem”</span>.
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