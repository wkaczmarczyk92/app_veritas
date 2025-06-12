<script setup>

import { ref } from 'vue';
import axios from 'axios';
import TextareaInput from '@/Components/TextareaInput.vue';
import Header from '@/Components/Templates/Header.vue';
import DonwloadProfileImageFromCRM from '@/Components/Images/DonwloadProfileImageFromCRM.vue';
import UploadProfileImageForUser from '@/Components/Images/UploadProfileImageForUser.vue';

import Processing from '@/Composables/Processing.vue';


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

const processing = ref(false)

</script>

<template>
    <v-card class="tw-shadow-lg tw-rounded tw-p-10 tw-mt-6 tw-relative" :loading="processing">
        <!-- <Processing v-if="processing" msg="Przetwarzanie..." /> -->
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
                <i class="fa-solid fa-image tw-text-sky-600"></i>
                <div>Zdjęcie profilowe użytkownika</div>
            </div>
        </template>
        <v-card-text>



            <div class="tw-flex tw-flex-row tw-gap-2">

                <DonwloadProfileImageFromCRM :user="user" @show-processing="processing = true"
                    @close-processing="processing = false" @update="$emit('update-user-profile-image', $event)">
                </DonwloadProfileImageFromCRM>
                <UploadProfileImageForUser :user="user" @show-processing="processing = true"
                    @close-processing="processing = false" @update="$emit('update-user-profile-image', $event)">
                </UploadProfileImageForUser>
            </div>


            <div class="tw-flex tw-flex-row tw-my-16">
                <div class="tw-grow tw-text-center tw-relative">
                    <div v-if="user.user_profile_image && user.user_profile_image.path"
                        class="profile-img profile-img-md tw-border-2 tw-border-gray-800 tw-shadow-xl tw-relative"
                        :style="`background-image: url(/user_profile_images/${user.user_profile_image.path});`">
                        <!-- <i v-if="user.user_profile_image && user.user_profile_image.status == 3" class="fa-solid fa-circle-check text-green-600 text-3xl image-icon"></i>
        <i v-else class="fa-sharp fa-solid fa-circle-xmark text-3xl image-icon"></i> -->
                    </div>
                    <i v-else class="fa-solid fa-circle-user img-default"></i>
                </div>
            </div>
            <div v-if="user.user_profile_image && user.user_profile_image.status != 1"
                class="tw-mt-4 tw-flex tw-flex-row tw-justify-center">
                <p v-if="user.user_profile_image.status == 2"
                    class="tw-bg-red-600 tw-text-white tw-fit-content tw-px-4 tw-py-2 tw-rounded-xl tw-shadow-xl">
                    <i class="fa-solid fa-circle-exclamation tw-mr-2"></i>
                    Zdjęcie zostało odrzucone<br>
                    <hr class="tw-my-2">
                    Powód:<br>
                <p class="tw-indent-2">
                    - {{ user.user_profile_image.decline_info }}
                </p>
                </p>
                <p v-else
                    class="tw-bg-green-600 tw-text-white tw-fit-content tw-px-4 tw-py-2 tw-rounded-xl tw-shadow-xl">
                    <i class="fa-sharp fa-solid fa-thumbs-up tw-mr-2"></i>
                    Zdjęcie zostało zaakceptowane
                </p>
            </div>
            <div class="tw-flex tw-flex-col tw-mt-6"
                v-if="user.user_profile_image && user.user_profile_image.status == 1">
                <div class="tw-grow tw-text-center tw-relative">
                    <button
                        class="tw-bg-green-800 tw-text-gray-100 tw-px-6 tw-py-2 tw-rounded hover:tw-bg-green-600 disabled:tw-bg-green-600  disabled:tw-text-gray-100 hover:tw-cursor-pointer"
                        @click="accept_user_image" :disabled="disabled">
                        <i class="fa-solid fa-camera tw-mr-2"></i>
                        Akceptuj zdjęcie
                    </button>
                    <button
                        class="tw-ml-2 tw-bg-red-800 tw-text-gray-100 tw-px-6 tw-py-2 tw-rounded hover:tw-bg-red-600 disabled:tw-bg-red-600  disabled:tw-text-gray-100 hover:tw-cursor-pointer"
                        @click="decline_user_image" :disabled="disabled">
                        <i class="fa-solid fa-camera-slash tw-mr-2"></i>
                        Odrzuć zdjęcie
                    </button>
                </div>
                <div class="tw-mt-4 tw-flex tw-flex-col">
                    <label for="">Powód odrzucenia?</label>
                    <span v-if="decline_error" class="tw-text-red-600">{{ decline_error }}</span>
                    <TextareaInput v-model="decline_info" :rows="3"></TextareaInput>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>
