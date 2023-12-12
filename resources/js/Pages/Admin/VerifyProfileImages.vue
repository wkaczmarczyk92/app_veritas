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

</script>

<template>
    <Head :title="`VeritasApp - weryfikacja zdjęć profilowych`" />
    <AdminLayout>
        <Transition name="slide-fade">
            <MButton
                v-if="ids.length > 0"
                :disabled="disabled"
                :value="btn_text()"
                add_class="floating-submit px-12 py-4 text-2xl rounded shadow-xl z-10"
                bg="bg-green-700"
                hover="hover:bg-green-600"
                color="text-white"
                icon="fa-sharp fa-regular fa-image"
                @click="submit()"
            ></MButton>
        </Transition>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Weryfikacja zdjęć profilowych</h2>
        </template>
        <div class="py-12">
            <div class="max-w-full mx-auto px-2 sm:px-6 lg:px-16">
                <div v-if="profile_images">
                    <Transition name="slide-fade">
                        <div v-if="profile_images.length > 0">
                            <MButton
                                :disabled="disabled"
                                value="Zaznacz/odznacz wszytkie"
                                add_class="mb-4"
                                icon="fa-solid fa-ballot-check"
                                bg="bg-blue-700"
                                hover="hover:bg-blue-600"
                                @click="toggle_all()"
                            ></MButton>
                            <TransitionGroup tag="ul" name="list" class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5">
                                <li v-for="(user, index) in profile_images" class="text-center relative border-2 border-blue-500 rounded-md bg-gray-200" :key="index" @click="pushID(user.id)">
                                    <div  :for="`item_${index}`" class="py-8">
                                        <div 
                                            class="profile-img profile-img-md border-2 border-gray-800 shadow-xl relative mt-8" 
                                            :style="`background-image: url(/user_profile_images/${user.user_profile_image.path});`">
                                        </div>
                                        <input type="checkbox" :id="`item_${index}`" class="verify-checkbox text-gray-800 focus:outline-0" :value="user.id" :checked="ids.includes(user.id)">
                                        <div class="my-6 text-gray-800 font-bold text-xl">
                                            {{ user.user_profiles.first_name }} <span class="text-blue-700">{{ user.user_profiles.last_name }}</span>
                                        </div>
                                        <p class="text-sm font-bold">Dodano:</p>
                                        <p class="text-sm font-bold">{{ format(user.user_profile_image.created_at) }}</p>
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