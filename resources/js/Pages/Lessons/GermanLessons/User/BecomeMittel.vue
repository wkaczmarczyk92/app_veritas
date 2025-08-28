<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Lessons from '@/Pages/Lessons/GermanLessons/Templates/Lessons.vue'

const props = defineProps({

})



</script>
<template>

    <Head title="VeritasApp - zasady programu 'Język szlifujesz – na szteli zyskujesz'" />
    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-2 tw-mx-auto tw-max-w-8xl md:tw-max-w-5xl sm:tw-px-6 lg:tw-px-8 ">
                <v-card class="!tw-py-8">
                    <div class="tw-flex tw-flex-col tw-gap-1 tw-font-bold tw-items-center">
                            <div class="tw-text-red-500 tw-text-base md:tw-text-xl">
                                Język szlifujesz – na szteli zyskujesz
                            </div>
                            <div>
                                Kurs Niemieckiego dla Opiekunek Veritas
                            </div>
                        </div>
                    <v-card-text>
                        <div class="tw-flex tw-flex-col tw-mt-4">
                            <div>
                                Z myślą o Opiekunkach i Opiekunach, którzy chcą poprawić swój poziom języka niemieckiego
                                i mieć <span class="tw-font-bold">większy wybór zleceń</span>, przygotowaliśmy <span
                                    class="tw-font-bold">specjalny program językowy</span> <span
                                    class="tw-text-red-500 tw-font-bold">“Język
                                    szlifujesz –
                                    na szteli
                                    zyskujesz”</span>. To
                                praktyczny kurs, który pozwoli Ci zdobyć status osoby komunikatywnie posługującej się
                                niemieckim – bez
                                stresu, bez presji i we własnym tempie.
                            </div>
                            <div class="tw-mt-10 tw-flex tw-items-center tw-justify-center">
                                <v-btn size="large" color="#2563eb" @click="router.get(route('user.german.lessons.index'))">Lekcje niemieckiego</v-btn>
                            </div>
                            <div class="tw-mt-10 tw-text-xl tw-font-bold">
                                Dlaczego warto?
                            </div>
                            <div class="tw-mt-4">
                                Rynek opieki w Niemczech zmienia się dynamicznie, a rodziny coraz częściej poszukują
                                Opiekunek i Opiekunów,
                                którzy potrafią swobodnie porozumieć się po niemiecku. Znajomość języka otwiera drzwi do
                                większej liczby
                                ofert i lepszych warunków współpracy.
                            </div>
                            <div class="tw-mt-4">
                                Dlatego przygotowaliśmy kurs, który pozwoli Ci:
                            </div>
                            <div>
                                <ul class="tw-font-bold tw-list-disc tw-mt-4 tw-p-4">
                                    <li>podnieść kwalifikacje językowe, </li>
                                    <li>otrzymać status <span class="tw-text-blue-600">MITTEL</span> – potwierdzający
                                        komunikatywny poziom
                                        języka, </li>
                                    <li>zwiększyć swoje szanse na bardziej atrakcyjne i pewne zlecenia, </li>
                                    <li>być rekomendowaną do sprawdzonych i pewnych zleceń. </li>
                                </ul>
                            </div>
                            <div class="tw-mt-4">
                                Po ukończeniu kursu i zdaniu egzaminów zostaniesz oznaczona w naszym systemie jako Mitel
                                Veritas. Oznacza
                                to, że firma oficjalnie potwierdza Twoją znajomość języka niemieckiego na poziomie
                                komunikatywnym. Dzięki
                                temu zyskasz dostęp do lepszych zleceń – także tych, które wcześniej były dla Ciebie
                                niedostępne.
                            </div>
                            <div class="tw-mt-10 tw-text-xl tw-font-bold tw-text-amber-800">
                                Dodatkowy bonus!
                            </div>
                            <div class="tw-mt-2 tw-text-amber-800">
                                Każda Opiekunka i każdy Opiekun, którzy ukończą kurs, pomyślnie przejdą test i wyjadą na
                                pierwsze zlecenie
                                po kursie, otrzymają <span class="tw-font-bold">dodatkowe 100 euro</span> premii!
                            </div>
                            <div class="tw-mt-2 tw-text-amber-800">
                                Bonus będzie wypłacany po przepracowaniu minimum 30 dni na pierwszym zleceniu po uprzednio zdanym kursie.
                            </div>
                            <div class="tw-mt-10 tw-font-bold tw-text-xl">
                                Jak wygląda kurs?
                            </div>
                            <div>
                                <ul class="tw-list-disc tw-mt-4 tw-p-4">
                                    <li><span class="tw-font-bold">Forma:</span> online, realizujesz go, kiedy chcesz –
                                        bez presji i bez
                                        terminów.</li>
                                    <li><span class="tw-font-bold">Zakres:</span> 17 praktycznych lekcji dopasowanych do
                                        pracy opiekuna
                                        seniora w Niemczech.</li>
                                    <li><span class="tw-font-bold">Po każdej lekcji:</span> krótki mini test dla
                                        utrwalenia wiedzy.</li>
                                    <li><span class="tw-font-bold">Egzamin online: </span>
                                        <ul class="tw-list-disc tw-px-4 [list-style-type:circle] tw-p-2">
                                            <li>20 pytań, </li>
                                            <li>10 minut na rozwiązanie, </li>
                                            <li>zaliczenie przy minimum 80% poprawnych odpowiedzi, </li>
                                            <li>możesz podchodzić do egzaminu dwa razy dziennie.</li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="tw-font-bold">Egzamin końcowy ustny: </span>
                                        <ul class="tw-list-disc tw-px-4 [list-style-type:circle] tw-p-2">
                                            <li>5 pytań z zakresu kursu, </li>
                                            <li>egzamin ustny trwa do 15 minut, </li>
                                            <li>zaliczenie przy minimum 4 poprawnych odpowiedziach, </li>
                                            <li>w przypadku negatywnego wyniku, będziesz mieć możliwość od razu
                                                umówienia się z osobą
                                                egzaminującą na kolejny termin poprawkowy. </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="tw-mt-4">
                                Kurs zawiera wszystkie niezbędne zwroty i wyrażenia, które rzeczywiście przydadzą Ci się
                                w codziennej pracy
                                – zarówno w rozmowach z podopiecznym, jego rodziną, jak i personelem medycznym.
                                Dodatkowo obejmuje także
                                podstawy języka niemieckiego, niezbędne do swobodnej komunikacji. Przy niezaliczeniu
                                testu ustnego będzie
                                możliwość umówienia się z osobą egzaminującą na następny termin poprawkowy.
                            </div>
                            <div class="tw-mt-10 tw-font-bold tw-text-xl">
                                Podsumowanie korzyści:
                            </div>
                            <div class="tw-flex tw-flex-col tw-font-bold tw-mt-4">
                                <div class="tw-flex tw-flex-row tw-gap-1 md:tw-items-center tw-items-top tw-mt-2 md:tw-mt-0">
                                    <i class="fas fa-check-square tw-text-lg tw-text-green-600"></i>
                                    <p class="tw-text-lg">Większy dostęp do lepszych zleceń</p>
                                </div>
                                <div class="tw-flex tw-flex-row tw-gap-1 md:tw-items-center tw-items-top tw-mt-2 md:tw-mt-0">
                                    <i class="fas fa-check-square tw-text-lg tw-text-green-600"></i>
                                    <p class="tw-text-lg">Status MITTEL Veritas w naszym systemi</p>
                                </div>
                                <div class="tw-flex tw-flex-row tw-gap-1 md:tw-items-center tw-items-top tw-mt-2 md:tw-mt-0">

                                    <i class="fas fa-check-square tw-text-lg tw-text-green-600"></i>
                                    <p class="tw-text-lg">Premia 100 euro po pierwszym wyjeździe</p>
                                </div>
                                <div class="tw-flex tw-flex-row tw-gap-1 md:tw-items-center tw-items-top tw-mt-2 md:tw-mt-0">

                                    <i class="fas fa-check-square tw-text-lg tw-text-green-600"></i>
                                    <p class="tw-text-lg"> Elastyczna nauka – uczysz się, kiedy chcesz</p>
                                </div>
                                <div class="tw-flex tw-flex-row tw-gap-1 md:tw-items-center tw-items-top tw-mt-2 md:tw-mt-0">

                                    <i class="fas fa-check-square tw-text-lg tw-text-green-600"></i>
                                    <p class="tw-text-lg"> Praktyczny niemiecki przydatny w opiece </p>
                                </div>
                            </div>
                            <div class="tw-font-bold tw-mt-16 tw-text-xl tw-text-center">
                                Zrób krok do przodu i zwiększ swoje szanse na rynku pracy opiekunek i opiekunów w
                                Niemczech!
                            </div>
                            <div class="tw-font-bold tw-mt-4 tw-text-xl tw-text-center">
                                Z Veritas to prostsze niż myślisz.
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </UserLayout>
</template>