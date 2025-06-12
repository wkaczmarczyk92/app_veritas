<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

import UserData from '@/Pages/Admin/Parts/View/UserData.vue';
import UserPoints from '@/Pages/Admin/Parts/View/UserPoints.vue';
import UserPointsHistory from '@/Pages/Admin/Parts/View/UserPointsHistory.vue';
import UserEdit from '@/Pages/Admin/Parts/Edit/UserEdit.vue';
import AlertSuccess from '@/Components/Functions/AlertSuccess.vue';
import UserPayoutRequests from '@/Pages/Admin/Parts/View/UserPayoutRequests.vue';
import UserBOKRequests from '@/Pages/Admin/Parts/View/UserBOKRequests.vue';
import UserContactForms from '@/Pages/Admin/Parts/View/UserContactForms.vue';
import UserFamilyRecommendations from '@/Pages/Admin/Parts/View/UserFamilyRecommendations.vue';
import UserCaretakerRecommendations from '@/Pages/Admin/Parts/View/UserCaretakerRecommendations.vue';
import UserProfileImage from '@/Pages/Admin/Parts/View/UserProfileImage.vue';
import PasswordChange from '@/Pages/Admin/Parts/View/PasswordChange.vue';

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
    },
    user_bonus: {
        type: [Object, null],
        required: true
    }
});

console.log('bopnus', props.user_bonus)

const user = ref(props.user);
const user_bonus = ref(props.user_bonus);
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

const tab = ref('user_data')

const bonus_processing = ref(false)

const activate_bonus = async () => {
    bonus_processing.value = true

    try {
        let response = await axios.post(route('userpoints.activate.by.admin', { user_id: user.value.id }))
        response = response.data

        userAlertStore.pushAlert(response)

        if (response.success) {
            user_bonus.value = []
        }
    } catch (error) {
        console.log(error)
        alert('Coś poszło nie tak. Spróbuj ponownie później.')
        bonus_processing.value = false
    }

    bonus_processing.value = false
}

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
        <div class="">
            <div class="tw-max-w-full">
                <div class="tw-flex tw-flex-col tw-justify-between tw-gap-4 tw-mb-4">
                    <!-- <div class="tw-text-2xl tw-font-bold tw-text-gray-800 lg:tw-text-4xl sm:tw-text-3xl username">
                        {{ `${user.user_profiles.first_name} ${user.user_profiles.last_name}` }}
                    </div> -->
                    <v-card class="!tw-rounded-none">
                        <v-toolbar color="primary"
                            :title="user.user_profiles.first_name + ' ' + user.user_profiles.last_name">
                        </v-toolbar>
                        <div class="d-flex flex-row">
                            <v-tabs v-model="tab" color="#2563eb" direction="vertical">
                                <v-tab value="user_data">Dane użytkownika</v-tab>
                                <v-tab value="points">Historia punktów</v-tab>
                                <v-tab value="change_password">Zmiana hasła</v-tab>
                                <v-tab value="bok">Zgłoszenia do BOK-u i wnioski o wypłatę</v-tab>
                                <v-tab value="contact_forms">Formularze kontaktowe</v-tab>
                                <v-tab value="family_recommendations">Polecenia rodzin</v-tab>
                                <v-tab value="caretaker_recommendations">Polecenia opiekunek</v-tab>
                                <v-tab value="bonus">Bonusy</v-tab>
                            </v-tabs>

                            <v-card-text>
                                <v-window v-model="tab" class="!tw-p-4">
                                    <v-window-item value="bonus">
                                        <div v-if="user_bonus.length <= 0">
                                            <v-alert color="info">
                                                Brak bonusów do aktywacji.
                                            </v-alert>
                                        </div>
                                        <div v-else>
                                            <v-btn color="primary" :disabled="bonus_processing"
                                                @click="activate_bonus()">
                                                Aktywuj bonusy
                                            </v-btn>
                                        </div>
                                    </v-window-item>
                                    <v-window-item value="change_password">
                                        <PasswordChange :user="user"></PasswordChange>
                                    </v-window-item>
                                    <v-window-item value="bok">
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
                                    </v-window-item>
                                    <v-window-item value="contact_forms">
                                        <Suspense>
                                            <UserContactForms #default :user="user"></UserContactForms>
                                            <template #fallback>
                                                <Loader class="tw-grow"></Loader>
                                            </template>
                                        </Suspense>
                                    </v-window-item>
                                    <v-window-item value="family_recommendations">
                                        <Suspense>
                                            <UserFamilyRecommendations #default :user="user">
                                            </UserFamilyRecommendations>
                                            <template #fallback>
                                                <Loader class="tw-grow"></Loader>
                                            </template>
                                        </Suspense>
                                    </v-window-item>
                                    <v-window-item value="caretaker_recommendations">
                                        <Suspense>
                                            <UserCaretakerRecommendations #default :user="user">
                                            </UserCaretakerRecommendations>
                                            <template #fallback>
                                                <Loader class="tw-grow"></Loader>
                                            </template>
                                        </Suspense>
                                    </v-window-item>
                                    <v-window-item value="points">
                                        <UserPointsHistory #default v-model:user="user"
                                            :user_points_records_count="user_points_records_count">
                                        </UserPointsHistory>
                                    </v-window-item>
                                    <v-window-item value="user_data">
                                        <div class="tw-grid tw-grid-cols-1 tw-gap-4 lg:tw-grid-cols-2 md:tw-gap-6">
                                            <Transition name="slide-fade" mode="out-in">
                                                <UserData v-if="!edit_user" :user="user"
                                                    @toggle-user="toggle_user_edit">
                                                </UserData>
                                                <UserEdit v-else v-model:user="user" @toggle-user="toggle_user_edit">
                                                </UserEdit>
                                            </Transition>

                                            <UserPoints :user="user" :levels="levels">
                                            </UserPoints>
                                        </div>
                                        <UserProfileImage :user="user"
                                            @update-user-profile-image="user.user_profile_image = $event">
                                        </UserProfileImage>
                                    </v-window-item>
                                </v-window>
                            </v-card-text>
                        </div>
                    </v-card>
                </div>

                <Transition name="slide-fade" mode="out-in">
                    <div v-if="view == 'user'" class="tw-grid tw-grid-cols-1 tw-gap-4 lg:tw-grid-cols-2 md:tw-gap-6">
                    </div>
                    <div v-else-if="view == 'password_change'">
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
                        <!-- <UserPointsHistory #default v-model:user="user"
                            :user_points_records_count="user_points_records_count">
                        </UserPointsHistory> -->
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
