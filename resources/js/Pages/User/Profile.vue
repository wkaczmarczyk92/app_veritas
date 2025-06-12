<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import UserLayoutNoCRMAccount from '@/Layouts/UserLayoutNoCRMAccount.vue';
import { Head } from '@inertiajs/vue3';
import MyPersonalData from './Homepage/Parts/MyPersonalData.vue';
import FreeAccountShow from './Profile/FreeAccountShow.vue';

const props = defineProps({
    layout: String,
    user_with_crm_account: Boolean,
    user: {
        type: Object,
        required: true
    }
})

const layout_name = props.layout == 'user' ? UserLayout : UserLayoutNoCRMAccount;

</script>
<template>

    <Head title="VeritasApp - moje dane" />
    <component :is="layout_name">
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-8xl md:tw-max-w-3xl sm:tw-px-6 lg:tw-px-8 ">
                <div v-if="user_with_crm_account">
                    <Suspense>
                        <MyPersonalData />
                    </Suspense>
                </div>
                <div v-else>
                    <FreeAccountShow :user="user" />
                </div>
            </div>
        </div>
    </component>
</template>