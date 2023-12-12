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
    router.get(route('post.edit',{
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

</script>

<template>
    <Head :title="`VeritasApp - wszystkie posty`" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Wszystkie posty</h2>
        </template>
        <div class="py-12">
            <div class="max-w-8xl sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 shadow-xl rounded sm:rounded-lg px-6 sm:px-20 pt-16 pb-8 sm:pb-12">
                    <h2 class="text-3xl text-gray-800 font-bold">
                        <i class="fa-solid fa-list-timeline text-indigo-600"></i>
                        Posty
                    </h2>
                    <hr class="my-4">

                    <div class="flex flex-row gap-2 mb-2">
                        <label>
                            <Checkbox v-model:checked="checked"></Checkbox>
                            <span class="ml-2 text-sm text-gray-600">Zmień kolejność postów</span>
                        </label>
                    </div>

                    <div class="overflow-x-auto relative" v-if="posts.length > 0">
                        <AbsoluteLoader v-if="loader_active" :text="text"></AbsoluteLoader>
                        <table class="text-center w-full border-collapse">
                            <thead>
                                <tr class="table-tr">
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        #</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                        Tytuł</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                        Wyświetlany od</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                        Wyświetlany do</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-center">
                                        Status</th>
                                    <th
                                        class="py-4 px-2 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        Akcje</th>
                                </tr>
                            </thead>
                            <draggable 
                                tag="tbody"
                                @change="update_order"
                                v-model="posts" 
                                @start="drag=true" 
                                @end="drag=false" 
                                :disabled="!checked"
                                item-key="id">
                                <template #item="{element}">
                                    <tr class="hover:bg-grey-lighter draggable-tr" :key="element.id">
                                        <td class="py-4 px-2 border-b border-grey-light">{{ element.id }}</td>
                                        <td class="py-4 px-2 border-b border-grey-light text-left">{{ element.title }}</td>
                                        <td class="py-4 px-2 border-b border-grey-light text-left">{{ element.start_at }}</td>
                                        <td class="py-4 px-2 border-b border-grey-light text-left">{{ element.end_at }}</td>
                                        <td class="py-4 px-2 border-b border-grey-light text-center">
                                            <i class="fa-solid fa-globe text-2xl text-blue-700" :class="element.type == 'draft' ? 'draft-post' : 'publish-post'"></i>
                                        </td>
                                        <td class="py-4 px-2 border-b border-grey-light">
                                            <i class="fa-sharp fa-solid fa-trash text-2xl mr-2 text-red-600 hover:text-red-800 hover:cursor-pointer" @click="remove(element)"></i>
                                            <i class="fa-solid fa-pen-to-square text-2xl text-blue-500 hover:text-blue-800 hover:cursor-pointer" @click="edit(element)"></i>
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


<!-- <section class="bg-gray-100 overflow-hidden shadow-xl rounded sm:rounded-lg mt-10"> -->