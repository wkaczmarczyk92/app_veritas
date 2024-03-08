<template>
    <BOKModal v-if="modalStore.visibility.bok" />

    <ContactFormModal v-if="modalStore.visibility.contact_form" />

    <ReadyToDepartureModal v-if="modalStore.visibility.ready_to_departure" />

    <PointsHistoryModal v-if="modalStore.visibility.points_history" />

    <UserCaretakerRecommendationModal v-if="modalStore.visibility.caretaker_recommendations" />
    <UserFamilyRecommendationsModal v-if="modalStore.visibility.family_recommendations" />

    <ProfileImageModal v-if="modalStore.visibility.profile_image"></ProfileImageModal>
</template>

<script setup>

import ReadyToDepartureModal from '@/Modals/ReadyToDepartureModal.vue';
import BOKModal from '@/Modals/BOKModal.vue';
import ContactFormModal from '@/Modals/ContactFormModal.vue';
import PointsHistoryModal from '@/Modals/PointsHistoryModal.vue';
import UserCaretakerRecommendationModal from '@/Modals/UserCaretakerRecommendationModal.vue';
import UserFamilyRecommendationsModal from '@/Modals/UserFamilyRecommendationsModal.vue';
import ProfileImageModal from '@/Modals/ProfileImageModal.vue';

import { useUserStore } from '@/Pinia/UserStore'
import { useModalStore } from '@/Pinia/ModalStore'

const modalStore = useModalStore();

const userStore = useUserStore();
await userStore.set_user();

</script>
