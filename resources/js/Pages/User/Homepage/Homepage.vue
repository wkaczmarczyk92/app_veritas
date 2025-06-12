<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, toRef, onBeforeMount } from 'vue';

import { useUserStore } from '@/Pinia/UserStore'

import Post from './Parts/Post.vue';
import PostNew from './Parts/PostNew.vue';
import ImportantInfo from './Parts/ImportantInfo.vue';
// import FilesToDownload from './Parts/FilesToDownload.vue';
import OnlineCourses from './Parts/OnlineCourses.vue';
import PayoutRequest from './Parts/PayoutRequest.vue';
import FamilyRecommendation from './Parts/FamilyRecommendation.vue';
import CaretakerRecommendation from './Parts/CaretakerRecommendation.vue';
import MyPersonalData from './Parts/MyPersonalData.vue';
import UserLogoCard from './Parts/UserLogoCard.vue';
import LevelTimeline from './Parts/LevelTimeline.vue';
// import Offers from './Parts/Offers.vue';


const props = defineProps({
    levels: {
        type: Object,
        required: true
    },
    payout_active: {
        type: Boolean,
        default: true
    },
    posts: {
        type: Object,
        required: true
    },
    recruiter: {
        type: Object,
        requried: true
    }
});

console.log('payout active', props.payout_active)

const user = toRef(props.user);

const toggle_modal = (modal_name) => {
    modal.value[modal_name] = !modal.value[modal_name];
}

const userStore = useUserStore();

onBeforeMount(async () => {
    await userStore.set_user();
})

</script>

<template>

    <Head title="VeritasApp - strona główna" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">

                <Suspense>
                    <UserLogoCard :levels="levels" @open="toggle_modal" />
                </Suspense>

                <Suspense>
                    <LevelTimeline :levels="levels" />
                </Suspense>

                <Suspense>
                    <PayoutRequest :payout_active="payout_active" />
                </Suspense>

                <PostNew v-for="(post, index) in posts" class="tw-mt-10" :key="index" :post="post" />

                <ImportantInfo :recruiter="recruiter" />

                <!-- <Suspense>
                    <Offers v-model:user="user" @open-modal="modal.ready_to_departure = true"></Offers>
                </Suspense>

                <MyPersonalData :user="user" @open="toggle_modal"></MyPersonalData>

                <div class="tw-grid tw-gap-10 tw-mt-10 lg:tw-grid-cols-2 sm:tw-gap-6">
                    <CaretakerRecommendation :bonus="bonus" v-model:model_value="modal.caretaker_recommendations">
                    </CaretakerRecommendation>

                    <FamilyRecommendation :bonus="bonus" v-model:model_value="modal.family_recommendations">
                    </FamilyRecommendation>
                </div>

                <Posts v-for="(post, index) in posts" class="tw-mt-10" :title="post.title" :body="post.body"
                    :label="post.post_labels.name" :post="post"></Posts>

                <OnlineCourses></OnlineCourses>

                <ImportantInfo :recruiter="recruiter"></ImportantInfo>

                <FilesToDownload></FilesToDownload> -->

            </div>
        </div>

    </UserLayout>
</template>
