<script setup>

import MButton from '@/Components/Buttons/MButton.vue';

const props = defineProps({
    profile_image: {
        type: [Object, null],
        required: true
    }
})

console.log(props.profile_image);
const url = () => {
    return (props.profile_image && props.profile_image.path) ? 'user_profile_images/' + props.profile_image.path : '';
}

</script>

<template>
    <div class="img-wrapper">
        <div v-if="profile_image && profile_image?.path && profile_image?.status == 3"
            class="profile-img tw-border-2 tw-border-gray-800 tw-shadow-2xl"
            :style="`background-image: url(${url()});`">
        </div>
        <div v-else
            class="tw-w-[160px] tw-h-[160px] tw-flex tw-flex-row tw-justify-center tw-items-center tw-bg-[#ffefdc] tw-rounded-full">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"
                    fill="#fc9003" />
                <path d="M12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z" fill="#fc9003" />
            </svg>
        </div>

    </div>
    <div v-if="profile_image && profile_image.status == 1" class="tw-mt-4 tw-flex tw-flex-row tw-justify-center">
        <MButton :disabled="true" bg="tw-bg-orange-600"
            hover="hover:tw-bg-orange-600 hover:tw-pointer-events-none hover:tw-cursor-none"
            class="tw-rounded-xl tw-border-orange-600" value="Zdjęcie w trakcie weryfikacji"
            icon="fa-sharp fa-solid fa-circle-info"></MButton>
    </div>
    <div v-if="profile_image && profile_image.status == 2" class="tw-mt-4 tw-flex tw-flex-row tw-justify-center">
        <MButton :disabled="true" bg="tw-bg-red-600" hover="hover:tw-bg-red-600" class="tw-rounded-xl tw-border-red-600"
            value="Zdjęcie zostało odrzucone" icon="fa-solid fa-circle-exclamation" subheader="Powód:"
            :text="profile_image.decline_info" :hr="true"></MButton>
    </div>
</template>
