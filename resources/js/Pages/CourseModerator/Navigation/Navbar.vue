<script setup>
import { ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia'

const showingNavigationDropdown = ref(false);

console.log(showingNavigationDropdown.value)

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
                        <NavLink :href="route('course_moderator.dashboard')" :active="route().current('course_moderator.dashboard')">
                            Pulpit
                        </NavLink>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Użytkownicy
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">

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
                                    <DropdownLink :href="route('course_moderator.create_user')"> Dodaj użytkownika</DropdownLink>
                                    <DropdownLink :href="route('course_moderator.users')"> List użytkowników</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Kompendia
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">

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
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Dodaj kompendium</DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> Lista kompendiów</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Vademeca
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">

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
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Dodaj vademecum</DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> List vademeców</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Testy
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">

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
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Dodaj test</DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> List testów</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Webinaria
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">

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
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Dodaj webinarium</DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> List webinariów</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <div
                            class="dropdown-item tw-ml-3 tw-relative tw-items-center tw-inline-flex tw-items-center tw-px-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    Pliki
                                    <span class="tw-inline-flex tw-rounded-md">
                                        <button type="button"
                                            class="tw-inline-flex tw-items-center tw-px-3 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-300 tw-bg-gray-800 hover:tw-text-gray-400 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">

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
                                    <DropdownLink :href="route('pointbreakpoints.index')"> Dodaj pliki</DropdownLink>
                                    <DropdownLink :href="route('level.bonus.value.index')"> List plików</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <!-- <NavLink :href="route('bokrequest.index')" :active="route().current('bokrequest.index')">
                            Wyszukiwarka
                        </NavLink> -->
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
            class="sm:tw-hidden">
            <div class="tw-pt-2 tw-pb-3 tw-space-y-1">
                <ResponsiveNavLink :href="route('course_moderator.dashboard')" :active="route().current('course_moderator.dashboard')">
                    Pulpit
                </ResponsiveNavLink>
                <!-- <ResponsiveNavLink :href="route('bokrequest.index')" :active="route().current('bokrequest.index')">
                    Zgłoszenia BOK
                </ResponsiveNavLink> -->
                <!-- <div
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
                            <DropdownLink :href="route('pointbreakpoints.index')"> Punkty kontrolne </DropdownLink>
                            <DropdownLink :href="route('level.bonus.value.index')"> Bonus za wejście na poziom
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div> -->
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
