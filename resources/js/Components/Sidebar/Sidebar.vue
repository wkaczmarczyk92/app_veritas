<script setup>

import Navbar from '@/Components/Navigation/Navbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
import Spinner from '@/Composables/Spinner.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, onBeforeMount } from 'vue'
import SidebarItem from '@/Components/Sidebar/SidebarItem.vue'
import SidebarHeader from '@/Components/Sidebar/SidebarHeader.vue'
import { storeToRefs } from 'pinia'

import { PasswordRequestStore } from '@/Pinia/PasswordRequestStore';
import { ProfileImageStore } from '@/Pinia/ProfileImageStore';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';

const usePayoutRequestStore = payoutRequestsStore();
const usePasswordRequestStore = PasswordRequestStore();
const useProfileImageStore = ProfileImageStore();

const { count_incomplete_payout_request, count_for_approval_payout_request } = storeToRefs(usePayoutRequestStore);
const { password_request_count } = storeToRefs(usePasswordRequestStore);
const { count_unverified } = storeToRefs(useProfileImageStore);

onBeforeMount(async () => {
    await usePasswordRequestStore.countPasswordRequests();
    await usePayoutRequestStore.countIncomplete();
    await usePayoutRequestStore.countForApproval();
    await useProfileImageStore.countUnverified();
    await count_unlocked()
})

const count_unlocked_caretaker_recommendations = ref(0)
const count_unlocked = async () => {
    try {
        let response = await axios.post(route('caretaker.recommendations.count.unlocked'))
        response = response.data

        console.log('unlocked', response)
        count_unlocked_caretaker_recommendations.value = response.unlocked
    } catch (error) {
        console.log('unlocked error', error)
    }
}

const is_sidebar_open = () => {
    let is_open = localStorage.getItem('sidebar_open');
    console.log('is open', is_open)

    if (is_open && is_open == "1") {
        console.log('is open', 1)
        return true;
    } else {
        console.log('is open', 0)
        return false;
    }
}

const siedebar_toggle = () => {
    if (open_sidebar.value) {
        localStorage.setItem('sidebar_open', 0)
        open_sidebar.value = false
    } else {
        localStorage.setItem('sidebar_open', 1)
        open_sidebar.value = true
    }
}

const open_sidebar = ref(is_sidebar_open())

const page = usePage();
console.log('page', page.props)

</script>


<template>
    <div :class="open_sidebar ? 'tw-z-10 tw-w-full tw-fixed md:tw-relative lg:tw-block lg:tw-w-80' : 'tw-w-14'"
        class="tw-bg-gray-900 tw-text-white tw-flex tw-flex-col lg:tw-block tw-transition-all tw-duration-400">

        <!-- Hamburger -->
        <div v-if="!open_sidebar" class="tw-fixed tw-w-12">
            <img src="/images/veritaslogosmall.png" alt="" class="tw-p-2">
            <div class="tw-flex tw-justify-center tw-items-center tw-absolute tw-left-[50%] -tw-translate-x-[50%]">
                <i class="fas fa-bars tw-text-2xl hover:tw-cursor-pointer hover:tw-text-blue-600"
                    @click="siedebar_toggle()"></i>
            </div>
        </div>


        <Transition name="fade" mode="out-in">
            <div v-if="open_sidebar"
                class="tw-flex-grow tw-overflow-y-auto tw-p-4 tw-space-y-6 tw-h-screen md:tw-h-auto"
                :class="open_sidebar ? '' : 'tw-opacity-0'">
                <!-- Logo / Header -->
                <div class="tw-text-end">
                    <i @click="siedebar_toggle()"
                        class="fas fa-times tw-text-2xl hover:tw-cursor-pointer hover:tw-text-red-600"></i>
                </div>
                <Link :href="route('dashboard')">
                <ApplicationLogo class="tw-block tw-w-4/5 tw-fill-current tw-text-center tw-mx-auto" />
                </Link>
                <!-- Sekcja 1 -->
                <div>
                    <SidebarHeader>Pulpit</SidebarHeader>
                    <SidebarItem class="" :href="route('dashboard')" :active="route().current('dashboard')">
                        <template v-slot:icon>
                            <i class="far fa-tachometer-alt-slow"></i>
                        </template>
                        <template v-slot:title>
                            Pulpit
                        </template>
                    </SidebarItem>
                </div>

                <!-- Sekcja 2 -->
                <div>
                    <SidebarHeader>Użytkownicy</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem class="" :href="route('users')" :active="route().current('users')">
                            <template v-slot:icon>
                                <i class="far fa-users"></i>
                            </template>
                            <template v-slot:title>
                                Wszyscy użytkownicy
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('last.login.index')"
                            :active="route().current('last.login.index')">
                            <template v-slot:icon>
                                <i class="fal fa-analytics"></i>
                            </template>
                            <template v-slot:title>
                                Statystyka logowań
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('password.request.index')"
                            :active="route().current('password.request.index')">
                            <template v-slot:icon>
                                <i class="far fa-lock-open-alt"></i>
                            </template>
                            <template v-slot:title>
                                Zmiany hasła
                            </template>
                            <template v-slot:badges v-if="password_request_count > 0">
                                <v-badge inline :content="password_request_count" v-if="password_request_count > 0"
                                    color="#16a34a">
                                </v-badge>
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('user.profiles.verify')"
                            :active="route().current('user.profiles.verify')">
                            <template v-slot:icon>
                                <i class="far fa-images"></i>
                            </template>
                            <template v-slot:title>
                                Weryfikacja zdjęć
                            </template>
                            <template v-slot:badges v-if="count_unverified > 0">
                                <v-badge inline :content="count_unverified" v-if="count_unverified > 0" color="#c026d3">
                                </v-badge>
                            </template>
                        </SidebarItem>
                        <SidebarItem v-if="$page.props.god_mode" class="" :href="route('users.create')"
                            :active="route().current('users.create')">
                            <template v-slot:icon>
                                <i class="fa-solid fa-user-plus"></i>
                            </template>
                            <template v-slot:title>
                                Utwórz uzytkownika
                            </template>
                        </SidebarItem>
                    </div>
                </div>

                <!-- Sekcja 2 -->
                <div>
                    <SidebarHeader>Posty</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem class="" :href="route('post.index')" :active="route().current('post.index')">
                            <template v-slot:icon>
                                <i class="far fa-mail-bulk"></i>
                            </template>
                            <template v-slot:title>
                                Wszystkie posty
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('post.create')" :active="route().current('post.create')">
                            <template v-slot:icon>
                                <i class="fas fa-plus"></i>
                            </template>
                            <template v-slot:title>
                                Dodaj nowy
                            </template>
                        </SidebarItem>
                    </div>
                </div>

                <!-- Sekcja 2 -->
                <div>
                    <SidebarHeader>Polecenia</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem class="" :href="route('family.recommendations.index')"
                            :active="route().current('family.recommendations.index')">
                            <template v-slot:icon>
                                <i class="fal fa-users-medical"></i>
                            </template>
                            <template v-slot:title>
                                Polecenia rodzin
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('caretaker.recommendations.index')"
                            :active="route().current('caretaker.recommendations.index')">
                            <template v-slot:icon>
                                <i class="far fa-user-check"></i>
                            </template>
                            <template v-slot:title>
                                Polecenia opiekunek
                            </template>
                            <template v-slot:badges
                                v-if="count_unlocked_caretaker_recommendations > 0">
                                <v-badge inline :content="count_unlocked_caretaker_recommendations" color="#16a34a">
                                    <v-tooltip activator="parent" location="top">Nowe</v-tooltip>
                                </v-badge>
                            </template>
                        </SidebarItem>
                    </div>
                </div>

                <!-- Sekcja 2 -->
                <div>
                    <SidebarHeader>Zgłoszenia</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem class="" :href="route('payout.requests.index')"
                            :active="route().current('payout.requests.index')">
                            <template v-slot:icon>
                                <i class="fas fa-envelope-open-dollar"></i>
                            </template>
                            <template v-slot:title>
                                Wypłaty
                            </template>
                            <template v-slot:badges
                                v-if="count_incomplete_payout_request > 0 || count_for_approval_payout_request > 0">
                                <v-badge inline :content="count_incomplete_payout_request"
                                    v-if="count_incomplete_payout_request > 0" color="#16a34a">
                                    <v-tooltip activator="parent" location="top">Oczekujące</v-tooltip>
                                </v-badge>
                                <v-badge inline :content="count_for_approval_payout_request"
                                    v-if="count_for_approval_payout_request > 0" color="#dc2626">
                                    <v-tooltip activator="parent" location="top">Do akceptacji</v-tooltip>
                                </v-badge>
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('offer.index')" :active="route().current('offer.index')">
                            <template v-slot:icon>
                                <i class="far fa-clipboard-list"></i>
                            </template>
                            <template v-slot:title>
                                Oferty
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('bokrequest.index')"
                            :active="route().current('bokrequest.index')">
                            <template v-slot:icon>
                                <i class="far fa-lightbulb-exclamation"></i>
                            </template>
                            <template v-slot:title>
                                BOK
                            </template>
                        </SidebarItem>
                    </div>
                </div>

                <!-- Sekcja 2 -->
                <div>
                    <SidebarHeader>Lekcje i kursy</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem class="" :href="route('german.lessons.index')"
                            :active="route().current('german.lessons.index')">
                            <template v-slot:icon>
                                <i class="far fa-chalkboard-teacher"></i>
                            </template>
                            <template v-slot:title>
                                Lekcje niemieckiego
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('german.tests.show')"
                            :active="route().current('german.tests.show')">
                            <template v-slot:icon>
                                <i class="fas fa-question"></i>
                            </template>
                            <template v-slot:title>
                                Test niemieckiego
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('test.results.index')"
                            :active="route().current('test.results.index')">
                            <template v-slot:icon>
                                <i class="fal fa-poll-people"></i>
                            </template>
                            <template v-slot:title>
                                Wyniki testów WWW
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('oral.exam.index.create')"
                            :active="route().current('oral.exam.index.create')">
                            <template v-slot:icon>
                                <i class="far fa-calendar-alt"></i>
                            </template>
                            <template v-slot:title>
                                Harmonogram testów ustnych
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('oral.exam.index')"
                            :active="route().current('oral.exam.index')">
                            <template v-slot:icon>
                                <i class="far fa-user-graduate"></i>
                            </template>
                            <template v-slot:title>
                                Wyniki testów ustnych
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('german.tests.settings')"
                            :active="route().current('german.tests.settings')">
                            <template v-slot:icon>
                                <i class="fa-solid fa-sliders-up"></i>
                            </template>
                            <template v-slot:title>
                                Ustawienia testu
                            </template>
                        </SidebarItem>
                    </div>
                </div>

                <!-- Sekcja 2 -->
                <div>
                    <SidebarHeader>Ustawienia</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem class="" :href="route('bonus.index')" :active="route().current('bonus.index')">
                            <template v-slot:icon>
                                <i class="fal fa-sack-dollar"></i>
                            </template>
                            <template v-slot:title>
                                Bonusy
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('level.bonus.value.index')"
                            :active="route().current('level.bonus.value.index')">
                            <template v-slot:icon>
                                <i class="fas fa-dollar-sign"></i>
                            </template>
                            <template v-slot:title>
                                Bonus za poziom
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('pointbreakpoints.index')"
                            :active="route().current('pointbreakpoints.index')">
                            <template v-slot:icon>
                                <i class="fal fa-bullseye-pointer"></i>
                            </template>
                            <template v-slot:title>
                                Punkty kontrolne
                            </template>
                        </SidebarItem>
                    </div>
                </div>
                <div>
                    <SidebarHeader>Zaawansowane</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem v-if="$page.props.god_mode" class="" :href="route('command')"
                            :active="route().current('command')">
                            <template v-slot:icon>
                                <i class="fas fa-wrench"></i>
                            </template>
                            <template v-slot:title>
                                Prerwa techniczna
                            </template>
                        </SidebarItem>
                        <SidebarItem v-if="$page.props.god_mode" class=""
                            :href="route('app.settings.sync.level.index')"
                            :active="route().current('app.settings.sync.level.index')">
                            <template v-slot:icon>
                                <i class="fas fa-sync"></i>
                            </template>
                            <template v-slot:title>
                                Synchronizacja poziomu
                            </template>
                        </SidebarItem>
                        <SidebarItem class="" :href="route('app.settings.points.check.index')"
                            :active="route().current('app.settings.points.check.index')">
                            <template v-slot:icon>
                                <i class="far fa-money-check-edit-alt"></i>
                            </template>
                            <template v-slot:title>
                                Sprawdź punkty
                            </template>
                        </SidebarItem>
                        <SidebarItem v-if="$page.props.god_mode" class=""
                            :href="route('app.settings.manual.payout.request.index')"
                            :active="route().current('app.settings.manual.payout.request.index')">
                            <template v-slot:icon>
                                <i class="fa-solid fa-file-check"></i>
                            </template>
                            <template v-slot:title>
                                Generowanie pliku z wypłatami
                            </template>
                        </SidebarItem>
                    </div>
                </div>
                <div>
                    <SidebarHeader>Wyloguj się</SidebarHeader>
                    <div class="tw-space-y-2">
                        <SidebarItem @click="router.post(route('logout'))">
                            <template v-slot:icon>
                                <i class="far fa-sign-out-alt"></i>
                            </template>
                            <template v-slot:title>
                                Wyloguj się
                            </template>
                        </SidebarItem>
                    </div>
                </div>
            </div>
        </Transition>
        <!-- </Transition> -->
    </div>
</template>