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

const data_text = ref('<i class="fa-solid fa-list-check mr-2"></i> Pokaż zgłoszenia do BOK-u i Wnioski o wypłatę');
const forms_text = ref('<i class="fa-solid fa-user-gear mr-2"></i> Pokaż dane użytkownika.');

const toggle_view = () => {
    view.value.view_data = !view.value.view_data;
    view.value.text = view.value.view_data ? data_text.value : forms_text.value;
    edit_user.value = false;
}

const show = ref(false);

watch(view, (new_value, old_value) => {
    edit_user.value = false;
})

</script>

<template>
    <Head :title="`VeritasApp - ${user.user_profiles.first_name} ${user.user_profiles.last_name}`" />
    <AdminLayout>        
        <div class="py-12">
            <div class="max-w-full mx-auto px-2 sm:px-6 lg:px-16">
                <div class="flex flex-col justify-between mb-4 gap-4">
                    <div class="font-bold text-gray-800 lg:text-4xl text-2xl sm:text-3xl username">
                        {{ `${user.user_profiles.first_name} ${user.user_profiles.last_name}` }}
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">

                        <MButton 
                            @click="view = 'password_change'" 
                            add_class="mt-4 md:mt-0 fit-content shadow-xl"
                            bg="bg-red-700"
                            hover="hover:bg-red-600"
                            icon="fa-sharp fa-solid fa-key"
                            value="Zmiana hasła"
                            >
                        </MButton>
                        <MButton 
                            @click="view = 'bokANDpayout'" 
                            add_class="mt-4 md:mt-0 fit-content shadow-xl"
                            bg="bg-amber-700"
                            hover="hover:bg-amber-600"
                            icon="fa-solid fa-list-check"
                            value="Zgłoszenia do BOK-u i Wnioski o wypłatę"
                            >
                        </MButton>

                        <MButton 
                            @click="view = 'forms'" 
                            add_class="mt-4 md:mt-0 fit-content shadow-xl"
                            bg="bg-blue-700"
                            hover="hover:bg-blue-600"    
                            icon="fa-sharp fa-solid fa-comments"
                            value="Formularze kontaktowe"
                            >
                        </MButton>
                        <MButton 
                            @click="view = 'family_recommendations'" 
                            add_class="mt-4 md:mt-0 fit-content shadow-xl"
                            bg="bg-emerald-700"
                            hover="hover:bg-emerald-600"         
                            icon="fa-sharp fa-regular fa-users"
                            value="Polecenia rodzin"
                            >
                        </MButton>
                        <MButton 
                            @click="view = 'caretaker_recommendations'" 
                            add_class="mt-4 md:mt-0 fit-content shadow-xl"
                            bg="bg-pink-700"
                            hover="hover:bg-pink-600"  
                            icon="fa-regular fa-user-group"
                            value="Polecenia opiekunek"
                            >
                        </MButton>
                        <MButton 
                            @click="view = 'points_history'" 
                            add_class="mt-4 md:mt-0 fit-content shadow-xl"
                            bg="bg-violet-700"
                            hover="hover:bg-violet-600"  
                            icon="fa-solid fa-chart-simple"
                            value="Historia punktów"     
                            >                            
                        </MButton>
                        <MButton 
                            @click="view = 'user'" 
                            add_class="mt-4 md:mt-0 border-gray-800"
                            bg="bg-gray-800"
                            hover="hover:bg-gray-700"  
                            icon="fa-solid fa-user-gear"
                            value="Dane użytkownika"     
                            >
                        </MButton>
                    </div>
                </div>

                <Transition name="slide-fade" mode="out-in">
                    <div v-if="view == 'user'" class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <Transition name="slide-fade" mode="out-in">
                                <UserData v-if="!edit_user"
                                    :user="user"
                                    @toggle-user="toggle_user_edit"
                                ></UserData>
                                <UserEdit v-else
                                    v-model:user="user"
                                    @toggle-user="toggle_user_edit"
                                ></UserEdit>
                            </Transition>

                            
                            <UserProfileImage
                                :user="user"
                                @update-user-profile-image="user.user_profile_image = $event">
                            </UserProfileImage>
                        </div>
                        

                        <UserPoints
                            :user="user"
                            :levels="levels">
                        </UserPoints>

                        <!-- <UserPointsHistory
                            v-model:user="user"
                            :user_points_records_count="user_points_records_count"
                            ></UserPointsHistory> -->
                    </div>
                    <div v-else-if="view == 'password_change'">
                        <PasswordChange 
                            :user="user"></PasswordChange>
                    </div>

                    <div v-else-if="view == 'bokANDpayout'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> -->
                            <Suspense>
                                <UserPayoutRequests #default
                                    :user="user"
                                    ></UserPayoutRequests>
                                <template #fallback>
                                    <Loader class="grow"></Loader>
                                </template>
                            </Suspense>
                            <Suspense>
                                <UserBOKRequests #default
                                    :user="user"
                                    ></UserBOKRequests>
                                <template #fallback>
                                    <Loader class="grow"></Loader>
                                </template>
                            </Suspense>
                        </div>
                    </div>
                    <div v-else-if="view == 'forms'">
                        <Suspense>
                            <UserContactForms #default
                                :user="user"
                                ></UserContactForms>
                            <template #fallback>
                                <Loader class="grow"></Loader>
                            </template>
                        </Suspense>
                    </div>
                    <div v-else-if="view == 'family_recommendations'">
                        <Suspense>
                            <UserFamilyRecommendations #default
                                :user="user"
                                ></UserFamilyRecommendations>
                            <template #fallback>
                                <Loader class="grow"></Loader>
                            </template>
                        </Suspense>
                    </div>
                    <div v-else-if="view == 'points_history'">
                        <UserPointsHistory #default
                            v-model:user="user"
                            :user_points_records_count="user_points_records_count"
                        ></UserPointsHistory>
                    </div>
                    <div v-else-if="view == 'caretaker_recommendations'">
                        <Suspense>
                            <UserCaretakerRecommendations #default
                                :user="user"
                                ></UserCaretakerRecommendations>
                            <template #fallback>
                                <Loader class="grow"></Loader>
                            </template>
                        </Suspense>
                    </div>
                </Transition>
                

            </div>
        </div>
    </AdminLayout>
</template>
