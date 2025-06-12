<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="tw-bg-gray-800 tw-border-b tw-border-gray-500">
        <!-- Primary Navigation Menu -->
        <div class="tw-w-full tw-mx-auto tw-p-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-flex tw-justify-between tw-h-16">
                <div class="tw-flex">
                    <!-- Logo -->
                    <div class="tw-shrink-0 tw-flex tw-items-center">
                        <Link :href="route('/')">
                        <ApplicationLogo class="tw-block tw-h-16 tw-w-auto tw-fill-current" />
                        </Link>
                    </div>

                    <div class="tw-hidden tw-space-x-8 lg:-tw-my-px lg:tw-ml-10 lg:tw-flex">
                        <NavLink :href="route('/')" :active="route().current('/')">
                            Strona główna
                        </NavLink>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Polecenia
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('caretakerrecommendation.create')"> Poleć opiekunkę/a
                                    </DropdownLink>
                                    <DropdownLink :href="route('familyrecommendation.create')"> Poleć rodzinę
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Profil
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('userprofile.show')"> Moje konto
                                    </DropdownLink>
                                    <DropdownLink :href="route('password.edit')"> Zmień hasło
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Oferty
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <!-- {{ $page.props.auth.user.name }} -->

                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('offer.user')"> Szukaj ofert
                                    </DropdownLink>
                                    <DropdownLink :href="route('my_offers_free_account')"> Moje zgłoszenia
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <!-- <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Lekcje
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                                            <svg class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('user.german.lessons.index')"> Lekcje niemieckiego
                                    </DropdownLink>
                                    <DropdownLink :href="route('my_offers_free_account')"> Rozwiąż test
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div> -->
                        <!-- <NavLink :href="route('offer.user')" :active="route().current('offer.user')">
                            Szukaj ofert
                        </NavLink> -->
                        <NavLink :href="route('crm.document.index')" :active="route().current('crm.document.index')">
                            Dokumenty
                        </NavLink>
                        <NavLink :href="route('courses')" :active="route().current('courses')">
                            Kursy
                        </NavLink>
                    </div>
                </div>
                <div class="-tw-mr-2 tw-flex tw-items-center lg:tw-hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-md tw-text-gray-400 hover:tw-text-gray-500 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 focus:tw-text-gray-500 tw-transition tw-duration-150 tw-ease-in-out">
                        <svg class="tw-h-6 tw-w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{
                                'tw-hidden': showingNavigationDropdown,
                                'tw-inline-flex': !showingNavigationDropdown,
                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{
                                'tw-hidden': !showingNavigationDropdown,
                                'tw-inline-flex': showingNavigationDropdown,
                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div :class="{ 'tw-block': showingNavigationDropdown, 'tw-hidden': !showingNavigationDropdown }"
            class="lg:tw-hidden">
            <div class="tw-pt-2 tw-pb-3 tw-space-y-1">
                <ResponsiveNavLink :href="route('/')" :active="route().current('/')">
                    Strona główna
                </ResponsiveNavLink>
                <!-- <ResponsiveNavLink :href="route('users')" :active="route().current('users')">
                    Użytkownicy
                </ResponsiveNavLink> -->
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
                            <DropdownLink :href="route('caretakerrecommendation.create')"> Poleć opiekunkę/a
                            </DropdownLink>
                            <DropdownLink :href="route('familyrecommendation.create')"> Poleć rodzinę
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
                <div
                    class="dropdown-item tw-relative tw-block tw-w-full tw-pl-3 tw-pr-4 tw-py-2 tw-border-l-4 tw-border-transparent tw-text-left tw-text-base tw-font-medium tw-text-gray-600 hover:tw-text-gray-800 hover:tw-bg-gray-50 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-800 focus:tw-bg-gray-50 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            Profil
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
                            <DropdownLink :href="route('userprofile.show')"> Moje konto
                            </DropdownLink>
                            <DropdownLink :href="route('password.edit')"> Zmień hasło
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
                <ResponsiveNavLink :href="route('offer.user')" :active="route().current('offer.user')">
                    Szukaj ofert
                </ResponsiveNavLink>
                <ResponsiveNavLink :href="route('crm.document.index')" :active="route().current('crm.document.index')">
                    Dokumenty
                </ResponsiveNavLink>
                <ResponsiveNavLink :href="route('courses')" :active="route().current('courses')">
                    Kursy
                </ResponsiveNavLink>
            </div>

            <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
                <!-- <div class="tw-px-4">
                    <div class="tw-font-medium tw-text-base tw-text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="tw-font-medium tw-text-sm tw-text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div> -->

                <div class="tw-mt-3 tw-space-y-1">
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Wyloguj się
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
