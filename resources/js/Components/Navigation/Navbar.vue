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
    <nav class="bg-gray-800 border-b border-gray-100">
        <div class="max-w-8xl mx-auto p-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                            <ApplicationLogo
                                class="block h-16 w-auto fill-current"
                            />
                        </Link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Pulpit
                        </NavLink>
                        <!-- <NavLink :href="route('users')" :active="route().current('users')">
                            Użytkownicy
                        </NavLink> -->
                        <div class="dropdown-item ml-3 relative items-center inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-gray-400 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Użytkownicy
                                    <span v-if="password_request_count" class="password-request-count-info text-white">{{ password_request_count }}</span>
                                    <span v-if="count_unverified" class="image-request-count-info text-white" :class="password_request_count ? 'image-wp-count' : 'image-wop-count'">{{ count_unverified }}</span>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-gray-400 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('users')"> Wszyscy użytkownicy </DropdownLink>
                                    <DropdownLink :href="route('last.login.index')"> Statystyka logowań </DropdownLink>
                                    <DropdownLink :href="route('user.profiles.verify')" class="relative"> Weryfikacja zdjęć profilowych 
                                        <span v-if="count_unverified" class="image-request-count-info-in-dropdown text-white">{{ count_unverified }}</span>
                                    </DropdownLink>
                                    <DropdownLink :href="route('password.request.index')" class="relative"> Zgłoszenia zmiany hasła
                                        <span v-if="password_request_count" class="password-request-count-info-in-dropdown text-white">{{ password_request_count }}</span>
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div class="dropdown-item ml-3 relative items-center inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-gray-400 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Posty
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-gray-400 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
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
                        <div class="dropdown-item ml-3 relative items-center inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-gray-400 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Polecenia
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-gray-400 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('caretaker.recommendations.index')"> Polecenia opiekunek </DropdownLink>
                                    <DropdownLink :href="route('family.recommendations.index')"> Polecenia rodzin </DropdownLink>
                                    <DropdownLink :href="route('bonus.index')"> Ustawienia bonusów </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <!-- <NavLink :href="route('post.index')" :active="route().current('post.index')">
                            Posty
                        </NavLink> -->
                        <NavLink :href="route('payoutrequest.index')" :active="route().current('payoutrequest.index')">
                            Wnioski o wypłatę
                            <span v-if="count_incomplete_payout_request" class="new-payout-request-info">{{ count_incomplete_payout_request }}</span>
                        </NavLink>
                        <NavLink :href="route('offer.index')" :active="route().current('offer.index')">
                            Zgłoszenia na oferty
                            <span v-if="count_incomplete_payout_request" class="new-payout-request-info">{{ count_incomplete_payout_request }}</span>
                        </NavLink>
                        <NavLink :href="route('bokrequest.index')" :active="route().current('bokrequest.index')">
                            Zgłoszenia BOK
                        </NavLink>
                        <div class="dropdown-item ml-3 relative items-center inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-gray-400 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Ustawienia
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-gray-400 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <!-- <DropdownLink :href="route('multiplier_settings')"> Mnożnik punktów </DropdownLink> -->
                                    <!-- <DropdownLink :href="route('settings.index')"> Minimalna ilość puntków i kwota </DropdownLink> -->
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Punkty kontrolne </DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> Bonus za wejście na poziom </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-gray-400 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
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
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div
            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    Pulpit
                </ResponsiveNavLink>
                <!-- <ResponsiveNavLink :href="route('users')" :active="route().current('users')">
                    Użytkownicy
                </ResponsiveNavLink> -->
                <div class="dropdown-item relative block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Użytkownicy
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-800 hover:bg-gray-50 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}

                                    <svg
                                        class="ml-2 -mr-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('users')"> Wszyscy użytkownicy </DropdownLink>
                            <DropdownLink :href="route('user.profiles.verify')"> Weryfikacja zdjęć profilowych </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
                <div class="dropdown-item relative block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Posty
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-800 hover:bg-gray-50 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}

                                    <svg
                                        class="ml-2 -mr-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
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
                <div class="dropdown-item relative block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Polecenia
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-800 hover:bg-gray-50 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}

                                    <svg
                                        class="ml-2 -mr-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('caretaker.recommendations.index')"> Polecenia opiekunek </DropdownLink>
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
                <div class="dropdown-item relative block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Ustawienia
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-800 hover:bg-gray-50 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}

                                    <svg
                                        class="ml-2 -mr-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <!-- <DropdownLink :href="route('multiplier_settings')"> Mnożnik punktów </DropdownLink> -->
                            <!-- <DropdownLink :href="route('settings.index')"> Minimalna ilość puntków i kwota </DropdownLink> -->
                            <DropdownLink :href="route('pointbreakpoints.index')"> Punkty kontrolne </DropdownLink>
                            <DropdownLink :href="route('level.bonus.value.index')"> Bonus za wejście na poziom </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Wyloguj się
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
