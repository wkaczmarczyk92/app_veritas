<template>

    <Head title="VeritasApp - moderator kursów - profil użytkownika" />
    <CourseModeratorLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1 tw-gap-4">
                <UserDataBox :user="user" :crm_id="user_profiles.crt_id_user_recruiter"
                    @connect-to-crm="user_profiles.crt_id_user_recruiter = $event" />
                <UserCompletedCoursesBox />
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import FlexData from '@/Templates/HTML/Data/FlexData.vue';
import FlexDataWithURL from '@/Templates/HTML/Data/FlexDataWithURL.vue';

import { username } from '@/Composables/Username.js'
import UserDataBox from './UserDataBox.vue';
import UserCompletedCoursesBox from './UserCompletedCoursesBox.vue';

import { toRefs } from 'vue'


const props = defineProps({
    user: {
        type: [Object],
        required: true
    }
})

const { user_profiles } = toRefs(props.user)

// console.log(user_profiles.value.first_name)

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('course_moderator.users')
    },
    {
        title: props.user.user_profiles.first_name + ' ' + props.user.user_profiles.last_name,
        disabled: false,
        href: route('course_moderator.user.show', { id: props.user.id })
    },
    {
        title: 'Profil',
        disabled: true
    }
]

// const username = () => {
//     return props.user.user_profiles.first_name + ' ' + props.user.user_profiles.last_name
// }

</script>
