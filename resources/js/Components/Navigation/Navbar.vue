<script setup>
import { ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { payoutRequestsStore } from '@/Pinia/StorePayoutRequest';
import { storeToRefs } from 'pinia'
import { PasswordRequestStore } from '@/Pinia/PasswordRequestStore';
import { ProfileImageStore } from '@/Pinia/ProfileImageStore';

const usePayoutRequestStore = payoutRequestsStore();
const usePasswordRequestStore = PasswordRequestStore();
const useProfileImageStore = ProfileImageStore();
// await usePasswordRequestStore.countPasswordRequests();

const { count_incomplete_payout_request } = storeToRefs(usePayoutRequestStore);
const { password_request_count } = storeToRefs(usePasswordRequestStore);
const { count_unverified } = storeToRefs(useProfileImageStore);
await usePasswordRequestStore.countPasswordRequests();
await usePayoutRequestStore.countIncomplete();
await useProfileImageStore.countUnverified();

console.log(count_unverified.value);

const showingNavigationDropdown = ref(false);

</script>

<template>
    <nav class="tw-bg-gray-800 tw-border-b tw-border-gray-100">
        <div class="tw-max-w-8xl tw-mx-auto tw-p-4 tw-sm:px-6 lg:tw-px-8">
            <div class="tw-flex tw-justify-between tw-h-16">
                <div class="tw-flex">
                    <div class="tw-shrink-0 tw-flex tw-items-center">
                        <Link :href="route('dashboard')">
                        <ApplicationLogo class="tw-block tw-h-16 tw-w-auto tw-fill-current" />
                        </Link>
                    </div>

                    <div class="tw-hidden tw-space-x-8 sm:-tw-my-px sm:tw-ml-10 sm:tw-flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Pulpit
                        </NavLink>
                        <!-- <NavLink :href="route('users')" :active="route().current('users')">
                            Użytkownicy
                        </NavLink> -->
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Użytkownicy
                                    <span v-if="password_request_count" class="password-request-count-info tw-text-white">{{
                                        password_request_count }}</span>
                                    <span v-if="count_unverified" class="image-request-count-info tw-text-white"
                                        :class="password_request_count ? 'image-wp-count' : 'image-wop-count'">{{
                                            count_unverified }}</span>
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('users')"> Wszyscy użytkownicy </DropdownLink>
                                    <DropdownLink :href="route('last.login.index')"> Statystyka logowań </DropdownLink>
                                    <DropdownLink :href="route('user.profiles.verify')" class="tw-relative"> Weryfikacja
                                        zdjęć
                                        profilowych
                                        <span v-if="count_unverified"
                                            class="image-request-count-info-in-dropdown tw-text-white">{{ count_unverified
                                            }}</span>
                                    </DropdownLink>
                                    <DropdownLink :href="route('password.request.index')" class="tw-relative"> Zgłoszenia
                                        zmiany hasła
                                        <span v-if="password_request_count"
                                            class="password-request-count-info-in-dropdown tw-text-white">{{
                                                password_request_count }}</span>
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Posty
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('post.index')"> Wszystkie posty </DropdownLink>
                                    <DropdownLink :href="route('post.create')"> Dodaj post </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Polecenia
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('caretaker.recommendations.index')"> Polecenia opiekunek
                                    </DropdownLink>
                                    <DropdownLink :href="route('family.recommendations.index')"> Polecenia rodzin
                                    </DropdownLink>
                                    <DropdownLink :href="route('bonus.index')"> Ustawienia bonusów </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <!-- <NavLink :href="route('post.index')" :active="route().current('post.index')">
                            Posty
                        </NavLink> -->
                        <NavLink :href="route('payoutrequest.index')" :active="route().current('payoutrequest.index')">
                            Wnioski o wypłatę
                            <span v-if="count_incomplete_payout_request" class="new-payout-request-info">{{
                                count_incomplete_payout_request }}</span>
                        </NavLink>
                        <NavLink :href="route('offer.index')" :active="route().current('offer.index')">
                            Zgłoszenia na oferty
                        </NavLink>
                        <NavLink :href="route('bokrequest.index')" :active="route().current('bokrequest.index')">
                            Zgłoszenia BOK
                        </NavLink>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Ustawienia
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <!-- <DropdownLink :href="route('multiplier_settings')"> Mnożnik punktów </DropdownLink> -->
                                    <!-- <DropdownLink :href="route('settings.index')"> Minimalna ilość puntków i kwota </DropdownLink> -->
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Punkty kontrolne </DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> Bonus za wejście na poziom
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>

                <div class="tw-hidden sm:tw-flex sm:tw-items-center sm:tw-ml-6">
                    <div class="tw-ml-3 tw-relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="tw-inline-flex tw-rounded-md">
                                    <button type="button"
                                        class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md !tw-text-gray-300  hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                        {{ $page.props.auth.user.name }}

                                        <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Wyloguj się
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
                <div class="-tw-mr-2 tw-flex tw-items-center sm:tw-hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-md tw-text-gray-400 hover:tw-text-gray-500 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 focus:tw-text-gray-500 tw-transition tw-duration-150 tw-ease-in-out">
                        <svg class="tw-h-6 tw-w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{
                                hidden: showingNavigationDropdown,
                                'tw-inline-flex': !showingNavigationDropdown,
                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{
                                hidden: !showingNavigationDropdown,
                                'tw-inline-flex': showingNavigationDropdown,
                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:tw-hidden">
            <div class="tw-pt-2 tw-pb-3 tw-space-y-1">
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    Pulpit
                </ResponsiveNavLink>
                <!-- <ResponsiveNavLink :href="route('users')" :active="route().current('users')">
                    Użytkownicy
                </ResponsiveNavLink> -->
                <div
                    class="dropdown-item tw-relative tw-block tw-w-full tw-pl-3 tw-pr-4 tw-py-2 tw-border-l-4 tw-border-transparent tw-text-left tw-text-base tw-font-medium tw-text-gray-600 hover:tw-text-gray-800 hover:tw-bg-gray-50 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-800 focus:tw-bg-gray-50 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Użytkownicy
                            <span class="tw-inline-flex tw-rounded-md">
                                <button type="button"
                                    class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-gray-800 hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                    {{ $page.props.auth.user.name }}

                                    <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('users')"> Wszyscy użytkownicy </DropdownLink>
                            <DropdownLink :href="route('user.profiles.verify')"> Weryfikacja zdjęć profilowych
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
                <div
                    class="dropdown-item tw-relative tw-block tw-w-full tw-pl-3 tw-pr-4 tw-py-2 tw-border-l-4 tw-border-transparent tw-text-left tw-text-base tw-font-medium tw-text-gray-600 hover:tw-text-gray-800 hover:tw-bg-gray-50 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-800 focus:tw-bg-gray-50 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Posty
                            <span class="tw-inline-flex tw-rounded-md">
                                <button type="button"
                                    class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-gray-800 hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                    {{ $page.props.auth.user.name }}

                                    <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('post.index')"> Wszystkie posty </DropdownLink>
                            <DropdownLink :href="route('post.create')"> Dodaj post </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
                <div
                    class="dropdown-item tw-relative tw-block tw-w-full tw-pl-3 tw-pr-4 tw-py-2 tw-border-l-4 tw-border-transparent tw-text-left tw-text-base tw-font-medium tw-text-gray-600 hover:tw-text-gray-800 hover:tw-bg-gray-50 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-800 focus:tw-bg-gray-50 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Polecenia
                            <span class="tw-inline-flex tw-rounded-md">
                                <button type="button"
                                    class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-gray-800 hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                    {{ $page.props.auth.user.name }}

                                    <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('caretaker.recommendations.index')"> Polecenia opiekunek
                            </DropdownLink>
                            <DropdownLink :href="route('family.recommendations.index')"> Polecenia rodzin </DropdownLink>
                            <DropdownLink :href="route('bonus.index')"> Ustawienia bonusów </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
                <ResponsiveNavLink :href="route('payoutrequest.index')" :active="route().current('payoutrequest.index')">
                    Wnioski o wypłatę
                </ResponsiveNavLink>
                <ResponsiveNavLink :href="route('bokrequest.index')" :active="route().current('bokrequest.index')">
                    Zgłoszenia BOK
                </ResponsiveNavLink>
                <div
                    class="dropdown-item tw-relative tw-block tw-w-full tw-pl-3 tw-pr-4 tw-py-2 tw-border-l-4 tw-border-transparent tw-text-left tw-text-base tw-font-medium tw-text-gray-600 hover:tw-text-gray-800 hover:tw-bg-gray-50 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-800 focus:tw-bg-gray-50 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Ustawienia
                            <span class="tw-inline-flex tw-rounded-md">
                                <button type="button"
                                    class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-gray-800 hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                    {{ $page.props.auth.user.name }}

                                    <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <!-- <DropdownLink :href="route('multiplier_settings')"> Mnożnik punktów </DropdownLink> -->
                            <!-- <DropdownLink :href="route('settings.index')"> Minimalna ilość puntków i kwota </DropdownLink> -->
                            <DropdownLink :href="route('pointbreakpoints.index')"> Punkty kontrolne </DropdownLink>
                            <DropdownLink :href="route('level.bonus.value.index')"> Bonus za wejście na poziom
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
                <div class="tw-px-4">
                    <div class="tw-font-medium tw-text-base tw-text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="tw-font-medium tw-text-sm tw-text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="tw-mt-3 tw-space-y-1">
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Wyloguj się
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
