<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

import UserData from './Parts/View/UserData.vue';
import UserPoints from './Parts/View/UserPoints.vue';
import UserPointsHistory from './Parts/View/UserPointsHistory.vue';
import UserEdit from './Parts/Edit/UserEdit.vue';
import AlertSuccess from '@/Components/Functions/AlertSuccess.vue';
import UserPayoutRequests from './Parts/View/UserPayoutRequests.vue';
import UserBOKRequests from './Parts/View/UserBOKRequests.vue';
import UserContactForms from './Parts/View/UserContactForms.vue';
import UserFamilyRecommendations from './Parts/View/UserFamilyRecommendations.vue';
import UserCaretakerRecommendations from './Parts/View/UserCaretakerRecommendations.vue';
import UserProfileImage from './Parts/View/UserProfileImage.vue';
import PasswordChange from './Parts/View/PasswordChange.vue';

import AlertDanger from '@/Components/Functions/AlertDanger.vue';

import Loader from '@/Components/Loader.vue';

import { AlertStore } from '@/Pinia/AlertStore';

import MButton from '@/Components/Buttons/MButton.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    levels: {
        type: Object,
        required: true
    },
    user_points_records_count: {
        type: Number,
        required: true
    }
});

const user = ref(props.user);
const edit_user = ref(false);

const userAlertStore = AlertStore();

const toggle_user_edit = () => {
    edit_user.value = !edit_user.value;
}

const view = ref('user');

const data_text = ref('<i class="mr-2 fa-solid fa-list-check"></i> Pokaż zgłoszenia do BOK-u i Wnioski o wypłatę');
const forms_text = ref('<i class="mr-2 fa-solid fa-user-gear"></i> Pokaż dane użytkownika.');

const toggle_view = () => {
    view.value.view_data = !view.value.view_data;
    view.value.text = view.value.view_data ? data_text.value : forms_text.value;
    edit_user.value = false;
}

const show = ref(false);

watch(view, (new_value, old_value) => {
    edit_user.value = false;
})

const active_breadcrumb = ref('Dane użytkownika')

const breadcrumbs = ref([
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('users')
    },
    {
        title: `${user.value.user_profiles.first_name} ${user.value.user_profiles.last_name}`,
        disabled: false,
        href: route('user', props.user.id)
    },
    {
        title: active_breadcrumb.value,
        disabled: true
    }
])

const get_breadcrumbs = computed(() => {
    console.log(breadcrumbs.value)
    return breadcrumbs.value
})

</script>

<template>
    <Head :title="`VeritasApp - ${user.user_profiles.first_name} ${user.user_profiles.last_name}`" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="get_breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-2 lg:tw-px-10">
                <div class="tw-flex tw-flex-col tw-justify-between tw-gap-4 tw-mb-4">
                    <div class="tw-text-2xl tw-font-bold tw-text-gray-800 lg:tw-text-4xl sm:tw-text-3xl username">
                        {{ `${user.user_profiles.first_name} ${user.user_profiles.last_name}` }}
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-2 sm:tw-flex-row sm:tw-justify-end">

                        <MButton @click="view = 'password_change'; breadcrumbs[3].title = 'Zmiana hasła'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-fit-content tw-shadow-xl`" bg="tw-bg-red-700"
                            hover="hover:tw-tw-bg-red-600" icon="fa-sharp fa-solid fa-key" value="Zmiana hasła">
                        </MButton>
                        <MButton
                            @click="view = 'bokANDpayout'; breadcrumbs[3].title = 'Zgłoszenia do BOK-u i Wnioski o wypłatę'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-fit-content tw-shadow-xl`" bg="tw-bg-amber-700"
                            hover="hover:tw-bg-amber-600" icon="fa-solid fa-list-check"
                            value="Zgłoszenia do BOK-u i Wnioski o wypłatę">
                        </MButton>

                        <MButton @click="view = 'forms'; breadcrumbs[3].title = 'Formularze kontaktowe'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-fit-content tw-shadow-xl`" bg="tw-bg-blue-700"
                            hover="hover:tw-bg-blue-600" icon="fa-sharp fa-solid fa-comments" value="Formularze kontaktowe">
                        </MButton>
                        <MButton @click="view = 'family_recommendations'; breadcrumbs[3].title = 'Polecenia rodzin'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-fit-content tw-shadow-xl`" bg="tw-bg-emerald-700"
                            hover="hover:tw-bg-emerald-600" icon="fa-sharp fa-regular fa-users" value="Polecenia rodzin">
                        </MButton>
                        <MButton @click="view = 'caretaker_recommendations'; breadcrumbs[3].title = 'Polecenia opiekunek'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-fit-content tw-shadow-xl`" bg="tw-bg-pink-700"
                            hover="hover:tw-bg-pink-600" icon="fa-regular fa-user-group" value="Polecenia opiekunek">
                        </MButton>
                        <MButton @click="view = 'points_history'; breadcrumbs[3].title = 'Historia punktów'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-fit-content tw-shadow-xl`" bg="tw-bg-violet-700"
                            hover="hover:tw-bg-violet-600" icon="fa-solid fa-chart-simple" value="Historia punktów">
                        </MButton>
                        <MButton @click="view = 'user'; breadcrumbs[3].title = 'Dane użytkownika'"
                            :add_class="`tw-mt-4 md:tw-mt-0 tw-border-gray-800`" bg="tw-bg-gray-800"
                            hover="hover:tw-bg-gray-700" icon="fa-solid fa-user-gear" value="Dane użytkownika">
                        </MButton>
                    </div>
                </div>

                <Transition name="slide-fade" mode="out-in">
                    <div v-if="view == 'user'" class="tw-grid tw-grid-cols-1 tw-gap-4 lg:tw-grid-cols-2 md:tw-gap-6">
                        <div>
                            <Transition name="slide-fade" mode="out-in">
                                <UserData v-if="!edit_user" :user="user" @toggle-user="toggle_user_edit"></UserData>
                                <UserEdit v-else v-model:user="user" @toggle-user="toggle_user_edit"></UserEdit>
                            </Transition>


                            <UserProfileImage :user="user" @update-user-profile-image="user.user_profile_image = $event">
                            </UserProfileImage>
                        </div>


                        <UserPoints :user="user" :levels="levels">
                        </UserPoints>

                        <!-- <UserPointsHistory
                            v-model:user="user"
                            :user_points_records_count="user_points_records_count"
                            ></UserPointsHistory> -->
                    </div>
                    <div v-else-if="view == 'password_change'">
                        <PasswordChange :user="user"></PasswordChange>
                    </div>

                    <div v-else-if="view == 'bokANDpayout'">
                        <div class="tw-grid tw-grid-cols-1 tw-gap-4 md:tw-grid-cols-2 sm:tw-gap-6">
                            <!-- <div class="grid grid-cols-1 gap-4 md:grid-cols-2"> -->
                            <Suspense>
                                <UserPayoutRequests #default :user="user"></UserPayoutRequests>
                                <template #fallback>
                                    <Loader class="tw-grow"></Loader>
                                </template>
                            </Suspense>
                            <Suspense>
                                <UserBOKRequests #default :user="user"></UserBOKRequests>
                                <template #fallback>
                                    <Loader class="tw-grow"></Loader>
                                </template>
                            </Suspense>
                        </div>
                    </div>
                    <div v-else-if="view == 'forms'">
                        <Suspense>
                            <UserContactForms #default :user="user"></UserContactForms>
                            <template #fallback>
                                <Loader class="tw-grow"></Loader>
                            </template>
                        </Suspense>
                    </div>
                    <div v-else-if="view == 'family_recommendations'">
                        <Suspense>
                            <UserFamilyRecommendations #default :user="user"></UserFamilyRecommendations>
                            <template #fallback>
                                <Loader class="tw-grow"></Loader>
                            </template>
                        </Suspense>
                    </div>
                    <div v-else-if="view == 'points_history'">
                        <UserPointsHistory #default v-model:user="user"
                            :user_points_records_count="user_points_records_count"></UserPointsHistory>
                    </div>
                    <div v-else-if="view == 'caretaker_recommendations'">
                        <Suspense>
                            <UserCaretakerRecommendations #default :user="user"></UserCaretakerRecommendations>
                            <template #fallback>
                                <Loader class="tw-grow"></Loader>
                            </template>
                        </Suspense>
                    </div>
                </Transition>


            </div>
        </div>
    </AdminLayout>
</template>
