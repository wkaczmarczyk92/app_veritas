<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import { computed, ref } from 'vue';
import MainContent from '@/Templates/HTML/MainContent.vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import { use_processing_store } from '@/Pinia/ProcessingStore';
import { use_users_store } from '@/Pinia/Admin/UsersStore.js';
import Spinner from '@/Components/Forms/Spinner.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    levels: {
        type: Object,
        required: true
    }
});

const processing_store = use_processing_store();
const users_store = use_users_store();
users_store.set_users(props.users)
const users = computed(() => users_store.get_users);
const search = ref('')

const headers = [
    {
        title: '#',
        value: 'id',
        sortable: true
    },
    {
        title: 'Użytkownik',
        value: 'user'
    },
    {
        title: 'Typ konta',
        value: 'account_type'
    },
    {
        title: 'Nr tel',
        value: 'user_profiles.phone_number'
    },
    {
        title: 'Poziom i punkty',
        value: 'level_and_points',
        sortable: true
    },
    {
        title: 'Rekruter',
        value: 'recruiter',
        sortable: true
    },
    {
        title: 'Utworzono',
        value: 'created_at',
        sortable: true
    }
];



const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('users')
    }
]

</script>

<template>

    <Head title="VeritasApp - użytkownicy" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <v-card title="Filtruj uzytkowników">
                <v-card-text class="!tw-pb-8">
                    <div class="tw-flex tw-gap-2 tw-items-center">
                        <div class="tw-w-full md:tw-w-1/3">
                            <v-text-field v-model="search" variant="outlined" label="Szukaj..." hide-details
                                @keyup.enter="users_store.search(search)" />
                        </div>
                        <div class="tw-flex tw-gap-2">
                            <v-btn size="x-large" color="#2563eb" @click="users_store.search(search)">Szukaj</v-btn>
                            <v-btn v-if="search" size="x-large" color="#dc2626"
                                @click="search = ''; users_store.destroy_search(search)">Usuń</v-btn>
                        </div>
                    </div>
                </v-card-text>
            </v-card>
            <div class="tw-relative">
                <Spinner v-if="processing_store.state" top_position="10" msg="Filtrowanie użytkowników" />
                <v-card variant="tonal" :color="'white'" class="tw-mb-4 tw-shadow-2xl !tw-mt-4" id="searchable">
                    <v-card-text class="!tw-p-0">
                        <div class="tw-overflow-x-auto" v-if="users">
                            <v-data-table :items="users" height="auto" item-value="id" :headers="headers"
                                items-per-page="50">

                                <!-- TYP KONTA -->
                                <template #item.account_type="{ item }">
                                    <div v-if="item.is_admin" class="tw-flex tw-flex-col tw-gap-1">
                                        <div v-for="(role, role_index) in item.roles" :key="role_index">
                                            <v-chip color="#e11d48" variant="tonal" rounded="lg">
                                                {{ role.name_pl }}
                                            </v-chip>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div v-if="item.user_profiles.crt_id_user_recruiter">
                                            <v-chip color="#2563eb" variant="outlined" rounded="lg">
                                                Premium
                                            </v-chip>
                                        </div>
                                        <div v-else>
                                            <v-chip color="#ea580c" variant="outlined" rounded="lg">
                                                Darmowe
                                            </v-chip>
                                        </div>
                                    </div>
                                </template>

                                <!-- POZIOM I PUNKTY -->
                                <template #item.level_and_points="{ item }">
                                    <span :class="levelColor(item.user_profiles.level)">
                                        <b>
                                            {{ level(levels, item.user_profiles.level).toUpperCase() }} - {{
                                            item.user_profiles.current_points }}
                                        </b>
                                    </span>
                                </template>

                                <!-- REKRUTER -->
                                <template #item.recruiter="{ item }">
                                    <div v-if="item.user_profiles.crt_id_user_recruiter">
                                        {{ item.user_profiles.recruiter_first_name }} {{
                                            item.user_profiles.recruiter_last_name }}
                                    </div>
                                    <div v-else class="tw-text-red-600">BRAK</div>
                                </template>

                                <!-- UŻYTKOWNIK -->
                                <template #item.user="{ item }">
                                    <div class="tw-flex tw-felx-row tw-gap-4 tw-items-center">
                                        <div>
                                            <div v-if="item.user_profile_image && item.user_profile_image.path && item.user_profile_image.status == 3"
                                                class="tw-relative tw-border-2 tw-border-gray-800 tw-shadow-xl profile-img profile-img-sm"
                                                :style="`background-image: url(user_profile_images/${item.user_profile_image.path});`">
                                            </div>
                                            <i v-else class="fa-solid fa-circle-user img-default img-default-sm"></i>
                                        </div>
                                        <div class="tw-flex tw-flex-col tw-gap-1">
                                            <a :href="route('user', { id: item.id })"
                                                class="tw-text-blue-600 hover:tw-underline hover:tw-text-blue-800">{{
                                                    item.user_profiles.full_name }}</a>
                                            <div v-if="item.pesel" href="">PESEL: <span class="tw-font-bold">{{ item.pesel ?? '-'
                                                    }}</span></div>
                                            <a v-else class="tw-text-purple-600 hover:tw-underline hover:tw-text-purple-800" :href="`mailto:${item.email}`">{{ item.email }}</a>
                                        </div>
                                        
                                    </div>
                                </template>
                                <template #item.created_at="{ value }">
                                    {{ format(value) }}
                                </template>
                            </v-data-table>
                        </div>
                        <v-alert color="info" v-else>
                            Brak użytkowników do wyświetlenia.
                        </v-alert>
                    </v-card-text>
                </v-card>
            </div>


        </MainContent>
    </AdminLayout>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.bounce-enter-active {
    animation: bounce-in 0.5s;
}

.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}

@keyframes bounce-in {
    0% {
        transform: scale(0);
    }

    50% {
        transform: scale(1.25);
    }

    100% {
        transform: scale(1);
    }
}
</style>
