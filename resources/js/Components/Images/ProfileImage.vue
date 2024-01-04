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
            class="profile-img tw-border-2 tw-border-gray-800 tw-shadow-2xl" :style="`background-image: url(${url()});`">
        </div>
        <i v-else class="fa-solid fa-circle-user img-default"></i>
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
