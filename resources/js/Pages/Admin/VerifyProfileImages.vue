<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ProfileImageStore } from '@/Pinia/ProfileImageStore';
import { ref, onMounted, computed } from 'vue';
import Loader from '@/Components/Loader.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { format } from '@/Components/Functions/DateFormat.vue';

const useProfileImageStore = ProfileImageStore();
const profile_images = ref(null);
const useAlertStore = AlertStore();

onMounted(async () => {
    profile_images.value = await useProfileImageStore.getNotVefiried();
})

const ids = ref([]);
const disabled = ref(false);

const pushID = (id) => {
    if (ids.value.includes(id)) {
        var index = ids.value.indexOf(id);
        if (index !== -1) {
            ids.value.splice(index, 1);
        }
    } else {
        ids.value.push(id);
    }
}

const toggle_all = () => {
    if (ids.value.length > 0) {
        ids.value = [];
    } else {
        let items = document.querySelectorAll('.verify-checkbox');
        items.forEach((item, index) => {
            if (ids.value.indexOf(item.value) === -1) {
                ids.value.push(parseInt(item.value));
            }
        })
    }
}

const submit = async () => {
    disabled.value = true;

    let response = await useProfileImageStore.massAccept(ids.value);
    useAlertStore.pushAlert(response.alert_type, response.msg);

    if (response.success) {
        ids.value = [];
        profile_images.value = await useProfileImageStore.getNotVefiried();
        useProfileImageStore.countUnverified();
    }

    disabled.value = false;
}

const btn_text = () => {
    return disabled.value ? 'Przetwarzanie...' : 'Zaakceptuj wybrane';
}

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Weryfikacja zdjęć profilowych',
        disabled: true
    }
]

const display_date = (updated_at, created_at) => {
    return updated_at ? format(updated_at) : format(created_at);
}

</script>

<template>
    <Head :title="`VeritasApp - weryfikacja zdjęć profilowych`" />
    <AdminLayout>
        <Transition name="slide-fade">
            <MButton v-if="ids.length > 0" :disabled="disabled" :value="btn_text()"
                add_class="floating-submit tw-px-12 tw-py-4 tw-text-2xl tw-rounded tw-shadow-xl z-10" bg="tw-bg-green-700"
                hover="hover:tw-bg-green-600" color="tw-text-white" icon="fa-sharp fa-regular fa-image" @click="submit()">
            </MButton>
        </Transition>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-2 tw-mx-auto sm:tw-px-6 lg:tw-px-16">
                <div v-if="profile_images">
                    <Transition name="slide-fade">
                        <div v-if="profile_images.length > 0">
                            <MButton :disabled="disabled" value="Zaznacz/odznacz wszytkie" add_class="mb-4"
                                icon="fa-solid fa-ballot-check" bg="tw-bg-blue-700" hover="hover:tw-bg-blue-600"
                                @click="toggle_all()"></MButton>
                            <TransitionGroup tag="ul" name="list"
                                class="tw-grid tw-grid-cols-1 tw-gap-4 sm:tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-3 xl:tw-grid-cols-5">
                                <li v-for="(user, index) in profile_images"
                                    class="tw-relative tw-text-center tw-bg-gray-200 tw-border-2 tw-border-blue-500 tw-rounded-md"
                                    :key="index" @click="pushID(user.id)">
                                    <div :for="`item_${index}`" class="tw-py-8">
                                        <div class="tw-relative tw-mt-8 tw-border-2 tw-border-gray-800 tw-shadow-xl profile-img profile-img-md"
                                            :style="`background-image: url(/user_profile_images/${user.user_profile_image.path});`">
                                        </div>
                                        <input type="checkbox" :id="`item_${index}`"
                                            class="tw-text-gray-800 verify-checkbox focus:tw-outline-0" :value="user.id"
                                            :checked="ids.includes(user.id)">
                                        <div class="tw-my-6 tw-text-xl tw-font-bold tw-text-gray-800">
                                            {{ user.user_profiles.first_name }} <span class="tw-text-blue-700">{{
                                                user.user_profiles.last_name }}</span>
                                        </div>
                                        <p class="tw-text-sm tw-font-bold">Dodano:</p>
                                        <p class="tw-text-sm tw-font-bold">{{ display_date(user.user_profile_image.updated_at, user.user_profile_image.created_at) }}
                                        </p>
                                    </div>
                                </li>
                            </TransitionGroup>
                        </div>
                        <div v-else>
                            <StaticInfoAlert>Brak zdjęć profilowych do weryfikacji.</StaticInfoAlert>
                        </div>
                    </Transition>
                </div>
                <Loader v-else></Loader>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
.list-move,
/* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
    position: absolute;
}
</style>
