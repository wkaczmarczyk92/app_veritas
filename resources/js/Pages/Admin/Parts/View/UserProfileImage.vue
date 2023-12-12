<script setup>

import { ref } from 'vue';
import axios from 'axios';
import TextareaInput from '@/Components/TextareaInput.vue';
import Header from '@/Components/Templates/Header.vue';
import DonwloadProfileImageFromCRM from '@/Components/Images/DonwloadProfileImageFromCRM.vue';
import UploadProfileImageForUser from '@/Components/Images/UploadProfileImageForUser.vue';


const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits([
    'update-user-profile-image'
])

const disabled = ref(false);

const accept_user_image = async () => {
    disabled.value = true;
    let response = await axios.patch(route('accept.user.profile.img'), {
        id: props.user.id,
        status: 3
    });

    emit('update-user-profile-image', response.data);
    disabled.value = false;
}

const decline_user_image = async () => {
    disabled.value = true;
    decline_error.value = '';

    if (decline_info.value == '' || decline_info.value == undefined) {
        decline_error.value = 'Uzupełnij pole aby odrzucić zdjęcie.';
        disabled.value = false;
        return;
    }

    let response = await axios.patch(route('decline.user.profile.img'), {
        id: props.user.id,
        status: 2,
        decline_info: decline_info.value
    });

    emit('update-user-profile-image', response.data);
    disabled.value = false;
}

const decline_info = ref('');
const decline_error = ref('');

</script>

<template>
    <div class="bg-gray-100 shadow-xl rounded p-10 mt-6">
        <Header
            h="3"
            value="Zdjęcie profilowe użytkownika"
            icon="fa-solid fa-image"
            icon_color="text-sky-600"
        ></Header>

        <div class="flex flex-row gap-2">

            <DonwloadProfileImageFromCRM
                :user="user"
                @update="$emit('update-user-profile-image', $event)"
            ></DonwloadProfileImageFromCRM>
            <UploadProfileImageForUser
                :user="user"
                @update="$emit('update-user-profile-image', $event)"
            ></UploadProfileImageForUser>
        </div>


        <div class="flex flex-row my-16">
            <div class="grow text-center relative">
                <div 
                    v-if="user.user_profile_image && user.user_profile_image.path" 
                    class="profile-img profile-img-md border-2 border-gray-800 shadow-xl relative" 
                    :style="`background-image: url(/user_profile_images/${user.user_profile_image.path});`">
                    <!-- <i v-if="user.user_profile_image && user.user_profile_image.status == 3" class="fa-solid fa-circle-check text-green-600 text-3xl image-icon"></i>
                    <i v-else class="fa-sharp fa-solid fa-circle-xmark text-3xl image-icon"></i> -->
                </div>
                <i v-else class="fa-solid fa-circle-user img-default"></i>
            </div>
        </div>
        <div v-if="user.user_profile_image && user.user_profile_image.status != 1" class="mt-4 flex flex-row justify-center">
                <p v-if="user.user_profile_image.status == 2" class="bg-red-600 text-white fit-content px-4 py-2 rounded-xl shadow-xl">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    Zdjęcie zostało odrzucone<br>
                    <hr class="my-2">
                    Powód:<br>
                    <p class="indent-2">
                        - {{ user.user_profile_image.decline_info }}
                    </p>
                </p>
                <p v-else class="bg-green-600 text-white fit-content px-4 py-2 rounded-xl shadow-xl">
                    <i class="fa-sharp fa-solid fa-thumbs-up mr-2"></i>
                    Zdjęcie zostało zaakceptowane
                </p>
            </div>
        <div class="flex flex-col mt-6" v-if="user.user_profile_image && user.user_profile_image.status == 1">
            <div class="grow text-center relative">
                <button class="bg-green-800 text-gray-100 px-6 py-2 rounded hover:bg-green-600 disabled:bg-green-600  disabled:text-gray-100 hover:cursor-pointer" @click="accept_user_image" :disabled="disabled">
                    <i class="fa-solid fa-camera mr-2"></i>
                    Akceptuj zdjęcie
                </button>
                <button class="ml-2 bg-red-800 text-gray-100 px-6 py-2 rounded hover:bg-red-600 disabled:bg-red-600  disabled:text-gray-100 hover:cursor-pointer" @click="decline_user_image" :disabled="disabled">
                    <i class="fa-solid fa-camera-slash mr-2"></i>
                    Odrzuć zdjęcie
                </button>
            </div>
            <div class="mt-4 flex flex-col">
                <label for="">Powód odrzucenia?</label>
                <span v-if="decline_error" class="text-red-600">{{ decline_error }}</span>
                <TextareaInput v-model="decline_info" :rows="3"></TextareaInput>
            </div>
        </div>
    </div>
</template>