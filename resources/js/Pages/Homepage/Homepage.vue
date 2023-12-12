<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, router  } from '@inertiajs/vue3';
import { ref, toRef } from 'vue';

import FloatingUserMenu from '@/Components/Templates/FloatingUserMenu.vue';

import Posts from './Parts/Posts.vue';
import ImportantInfo from './Parts/ImportantInfo.vue';
import FilesToDownload from './Parts/FilesToDownload.vue';
import OnlineCourses from './Parts/OnlineCourses.vue';
import PayoutRequest from './Parts/PayoutRequest.vue';
import FamilyRecommendation from './Parts/FamilyRecommendation.vue';
import CaretakerRecommendation from './Parts/CaretakerRecommendation.vue';
import MyPersonalData from './Parts/MyPersonalData.vue';
import UserLogoCard from './Parts/UserLogoCard.vue';
import LevelTimeline from './Parts/LevelTimeline.vue';
import Offers from './Parts/Offers.vue';

import BOKModal from '@/Modals/BOKModal.vue';
import ContactFormModal from '@/Modals/ContactFormModal.vue';
import PointsHistoryModal from '@/Modals/PointsHistoryModal.vue';
import UserCaretakerRecommendationModal from '@/Modals/UserCaretakerRecommendationModal.vue';
import UserFamilyRecommendationsModal from '@/Modals/UserFamilyRecommendationsModal.vue';
import ReadyToDepartureModal from '@/Modals/ReadyToDepartureModal.vue';
import ProfileImageModal from '@/Modals/ProfileImageModal.vue';

const modal = ref({
    bok: false,
    contact_form: false,
    ready_to_departure: false,
    points_history: false,
    profile_image: false,
    family_recommendations: false,
    caretaker_recommendations: false
});

const caretaker_recommendations_modal= ref(false);
const props = defineProps({
    user: {
        type: [Array, Object],
        required: true
    },
    levels: {
        type: Object,
        required: true
    },
    recruiter: {
        type: Object,
        required: true
    },
    posts: {
        type: Object
    },
    payout_active: {
        type: Boolean,
        default: true
    },
    bonus: {
        type: Object
    }
});

const user = toRef(props.user);

const toggle_modal = (modal_name) => {
    modal.value[modal_name] = !modal.value[modal_name];
}

</script>

<template>
    <Head title="VeritasApp - strona główna" />

    <UserLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto p-4 sm:px-6 lg:px-8 ">

                <UserLogoCard
                    :user="user"
                    :levels="levels"
                    @open="toggle_modal"
                ></UserLogoCard>

                <LevelTimeline
                    :levels="levels"
                    :user="user"
                ></LevelTimeline>

                <Suspense>
                    <PayoutRequest 
                        v-model:current_points="user.user_profiles.current_points"
                        :user="user"
                        :payout_active="payout_active"
                    ></PayoutRequest>
                </Suspense>

                <Suspense>
                    <Offers
                        v-model:user="user"   
                        @open-modal="modal.ready_to_departure = true" 
                    ></Offers>
                </Suspense>

                <MyPersonalData 
                    :user="user"
                    @open="toggle_modal"
                ></MyPersonalData>

                <div class="grid lg:grid-cols-2 sm:gap-6 gap-10 mt-10">
                    <CaretakerRecommendation
                        :bonus="bonus"
                        v-model:model_value="modal.caretaker_recommendations"
                    ></CaretakerRecommendation>

                    <FamilyRecommendation
                        :bonus="bonus"
                        v-model:model_value="modal.family_recommendations"
                    ></FamilyRecommendation>
                </div>

                <Posts v-for="(post, index) in posts"
                    class="mt-10"
                    :title="post.title"
                    :body="post.body"
                    :label="post.post_labels.name"
                    :post="post"
                ></Posts> 
                
                <OnlineCourses></OnlineCourses>
                
                <ImportantInfo
                    :recruiter="recruiter"
                ></ImportantInfo>

                <FilesToDownload></FilesToDownload>

            </div>
        </div>

    </UserLayout>

    <FloatingUserMenu
        @toggle="toggle_modal"
    ></FloatingUserMenu>

    <BOKModal
        v-if="modal.bok"
        v-model:model_value="modal.bok"   
    ></BOKModal>

    <ContactFormModal
        v-if="modal.contact_form"
        v-model:model_value="modal.contact_form"
    ></ContactFormModal>

    <ReadyToDepartureModal
        v-if="modal.ready_to_departure"
        v-model:model_value="modal.ready_to_departure"
        :ready_to_departure_dates="user?.ready_to_departure_dates"
        @update-ready-to-departure-date="user.ready_to_departure_dates = $event"
    ></ReadyToDepartureModal>

    <PointsHistoryModal
        v-if="modal.points_history"
        v-model:model_value="modal.points_history"
        :user="user"
    ></PointsHistoryModal>
        
    <UserCaretakerRecommendationModal
        v-if="modal.caretaker_recommendations"
        v-model:model_value="modal.caretaker_recommendations"
        :user="user"
    ></UserCaretakerRecommendationModal>

    <UserFamilyRecommendationsModal
        v-if="modal.family_recommendations"
        v-model:model_value="modal.family_recommendations"
        :user="user"
        @close="toggle_modal"
    ></UserFamilyRecommendationsModal>

    <ProfileImageModal
        v-if="modal.profile_image"
        v-model:model_value="modal.profile_image"
        :user="user"
        @update="user.user_profile_image = $event"
    ></ProfileImageModal>
</template>
