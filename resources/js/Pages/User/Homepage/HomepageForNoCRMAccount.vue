<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, toRef, onBeforeMount } from 'vue';

import { useUserStore } from '@/Pinia/UserStore'

import Post from './Parts/PostNew.vue';
import ImportantInfo from './Parts/ImportantInfo.vue';
// import FilesToDownload from './Parts/FilesToDownload.vue';
import OnlineCourses from './Parts/OnlineCourses.vue';
import PayoutRequest from './Parts/PayoutRequest.vue';
import FamilyRecommendation from './Parts/FamilyRecommendation.vue';
import CaretakerRecommendation from './Parts/CaretakerRecommendation.vue';
import MyPersonalData from './Parts/MyPersonalData.vue';
import UserLogoCardFreeAccount from './Parts/UserLogoCardFreeAccount.vue';
import LevelTimeline from './Parts/LevelTimeline.vue';
import UserLayoutNoCRMAccount from '@/Layouts/UserLayoutNoCRMAccount.vue';
// import Offers from './Parts/Offers.vue';


const props = defineProps({
    posts: {
        type: Object,
        required: true
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

    <UserLayoutNoCRMAccount>
        <div class="tw-py-12">
            <div class="!tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">

                <Suspense>
                    <UserLogoCardFreeAccount #default />
                    <template #fallback>
                        Ładowanie...
                    </template>
                </Suspense>

                <div class="tw-my-10"></div>

                <Post v-for="(post, index) in posts" class="tw-mt-10" :key="index" :post="post" />

            </div>
        </div>

    </UserLayoutNoCRMAccount>
</template>
