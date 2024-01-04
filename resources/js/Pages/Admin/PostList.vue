<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import '@vuepic/vue-datepicker/dist/main.css'
import Checkbox from '@/Components/Checkbox.vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import draggable from 'vuedraggable';
import AbsoluteLoader from '@/Components/AbsoluteLoader.vue';
import axios from 'axios';

import AlertInfo from '@/Components/Functions/AlertInfo.vue';

import AlertSuccess from '@/Components/Functions/AlertSuccess.vue';
import AlertDanger from '@/Components/Functions/AlertDanger.vue';

import { AlertStore } from '@/Pinia/AlertStore';

const props = defineProps({
    data: {
        type: Object,
        default: {}
    },
    post_labels: {
        type: Object,
        required: true
    }
});

// const post_type = ref({
//     publish: 'opublikowane',
//     draft: 'szkic'
// });

const useAlertStore = AlertStore();

const posts = ref(props.data);
console.log(posts.value);
const post_rollback = ref({});

watch(posts, (new_value, old_value) => {
    post_rollback.value = old_value;
})

const reload = () => {
    axios.get(route('post.all'))
        .then(response => {
            posts.value = response.data;
        })
}

const checked = ref(false);
const remove = (item) => {
    if (confirm('Na pewno chcesz usunąć ten post?')) {
        text.value = 'Usuwanie posta';
        loader_active.value = true;

        axios.delete(route('post.destroy', {
            id: item.id
        })).then(response => {
            loader_active.value = false;

            if (response.data.success) {
                useAlertStore.pushAlert('success', 'Post został usunięty.');
                reload();
            }

            if (!response.data.success) {
                useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Post nie został usunięty.');
            }
        })
    }
}

const edit = (item) => {
    router.get(route('post.edit', {
        id: item.id
    }));
}

const text = ref('Zmiana kolejności postów');

const update_order = () => {
    loader_active.value = true;
    text.value = 'Zmiana kolejności postów';

    axios.patch(route('post.order.update'), {
        posts: posts.value
    }).then(response => {
        loader_active.value = false;

        if (response.data.success) {
            useAlertStore.pushAlert('success', 'Kolejność została zmieniona.');
        }

        if (!response.data.success) {
            useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Kolejnośc nie została zmieniona.');
            posts.value = post_rollback.value;
        }
    });
}
const loader_active = ref(false);

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Wszystkie posty',
        disabled: true,
    }
]

</script>

<template>
    <Head :title="`VeritasApp - wszystkie posty`" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-px-4 tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-px-6 tw-pt-16 tw-pb-8 tw-bg-gray-100 tw-rounded tw-shadow-xl sm:tw-rounded-lg sm:tw-px-20 sm:tw-pb-12">
                    <h2 class="tw-text-3xl tw-font-bold tw-text-gray-800">
                        <i class="tw-text-indigo-600 fa-solid fa-list-timeline"></i>
                        Posty
                    </h2>
                    <hr class="tw-my-4">

                    <div class="tw-flex tw-flex-row tw-gap-2 tw-mb-2">
                        <label>
                            <Checkbox v-model:checked="checked"></Checkbox>
                            <span class="tw-ml-2 tw-text-sm tw-text-gray-600">Zmień kolejność postów</span>
                        </label>
                    </div>

                    <div class="relative overflow-x-auto" v-if="posts.length > 0">
                        <AbsoluteLoader v-if="loader_active" :text="text"></AbsoluteLoader>
                        <table class="tw-w-full tw-text-center tw-border-collapse">
                            <thead>
                                <tr class="table-tr">
                                    <th
                                        class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                        #</th>
                                    <th
                                        class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-text-left tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                        Tytuł</th>
                                    <th
                                        class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-text-left tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                        Wyświetlany od</th>
                                    <th
                                        class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-text-left tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                        Wyświetlany do</th>
                                    <th
                                        class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-text-center tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                        Status</th>
                                    <th
                                        class="tw-px-2 tw-py-4 tw-text-sm tw-font-bold tw-uppercase tw-border-b tw-bg-grey-lightest tw-text-grey-dark tw-border-grey-light">
                                        Akcje</th>
                                </tr>
                            </thead>
                            <draggable tag="tbody" @change="update_order" v-model="posts" @start="drag = true"
                                @end="drag = false" :disabled="!checked" item-key="id">
                                <template #item="{ element }">
                                    <tr class="hover:tw-bg-grey-lighter tw-draggable-tr" :key="element.id">
                                        <td class="tw-px-2 tw-py-4 tw-border-b tw-border-grey-light">{{ element.id }}</td>
                                        <td class="tw-px-2 tw-py-4 tw-text-left tw-border-b tw-border-grey-light">{{
                                            element.title }}</td>
                                        <td class="tw-px-2 tw-py-4 tw-text-left tw-border-b tw-border-grey-light">{{
                                            element.start_at }}
                                        </td>
                                        <td class="tw-px-2 tw-py-4 tw-text-left tw-border-b tw-border-grey-light">{{
                                            element.end_at }}</td>
                                        <td class="tw-px-2 tw-py-4 tw-text-center tw-border-b tw-border-grey-light">
                                            <i class="tw-text-2xl tw-text-blue-700 fa-solid fa-globe"
                                                :class="element.type == 'draft' ? 'draft-post' : 'publish-post'"></i>
                                        </td>
                                        <td class="tw-px-2 tw-py-4 tw-border-b tw-border-grey-light">
                                            <i class="tw-mr-2 tw-text-2xl tw-text-red-600 fa-sharp fa-solid fa-trash hover:tw-text-red-800 hover:tw-cursor-pointer"
                                                @click="remove(element)"></i>
                                            <i class="tw-text-2xl tw-text-blue-500 fa-solid fa-pen-to-square hover:tw-text-blue-800 hover:tw-cursor-pointer"
                                                @click="edit(element)"></i>
                                        </td>
                                    </tr>
                                </template>
                            </draggable>



                            <!-- More rows... -->
                        </table>
                    </div>
                    <AlertInfo v-else>Brak postów do wyświetlenia.</AlertInfo>


                </div>
            </div>
        </div>

    </AdminLayout>
</template>


<!-- <section class="mt-10 overflow-hidden bg-gray-100 rounded shadow-xl sm:rounded-lg"> -->
