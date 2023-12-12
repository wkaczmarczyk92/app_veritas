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
        <div v-if="profile_image && profile_image?.path && profile_image?.status == 3" class="profile-img border-2 border-gray-800 shadow-2xl" :style="`background-image: url(${url()});`"></div>
        <i v-else class="fa-solid fa-circle-user img-default"></i>
    </div>
    <div v-if="profile_image && profile_image.status == 1" class="mt-4 flex flex-row justify-center">
        <MButton
            :disabled="true"
            bg="bg-orange-600"
            hover="hover:bg-orange-600 hover:pointer-events-none hover:cursor-none"
            class="rounded-xl border-orange-600"
            value="Zdjęcie w trakcie weryfikacji"
            icon="fa-sharp fa-solid fa-circle-info"
        ></MButton>
    </div>
    <div v-if="profile_image && profile_image.status == 2" class="mt-4 flex flex-row justify-center">
        <MButton
            :disabled="true"
            bg="bg-red-600"
            hover="hover:bg-red-600"
            class="rounded-xl border-red-600"
            value="Zdjęcie zostało odrzucone"
            icon="fa-solid fa-circle-exclamation"
            subheader="Powód:"
            :text="profile_image.decline_info"
            :hr="true"
        ></MButton>
    </div>
</template>