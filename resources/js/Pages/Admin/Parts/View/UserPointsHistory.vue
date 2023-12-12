<script setup>

import { ref, watch } from 'vue'
import AlertInfo from '@/Components/Functions/AlertInfo.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { AlertStore } from '@/Pinia/AlertStore';


const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    user_points_records_count: {
        type: Number,
        required: true
    }
})

const emit = defineEmits([
    'update:user'
]);

const user = ref(props.user);
const errors = ref({});
const useAlertStore = AlertStore();

const spinner_visible = ref(false);
const points = ref([]);
const points_record_count = ref(props.user_points_records_count);

const per_page = 10;
var max_page = Math.ceil(points_record_count.value / per_page);
var current_page = ref(1);

const init_points_to_store = {
    value: '',
    type: null,
    comment: '',
    add_comment: false,
    user_id: props.user.id
}

const points_to_store = ref({...init_points_to_store});
points.value = props.user.user_points;

watch(current_page, (new_value, old_value) => {
    userPoints(new_value);
})

async function userPoints(page) {
    spinner_visible.value = true;
    let data = {
        current_page: page,
        user_id: props.user.id
    };

    let response = await axios.post('/punkty', data)
    points.value = response.data.user_points;
    spinner_visible.value = false;
    current_page.value = page;
}

function isAuto(item) {
    return item.auto ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>';
}

function formatDate(date) {
    const dateObj = new Date(date);
    const year = dateObj.getFullYear();
    const month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
    const day = dateObj.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function displayPoints(points, type) {
    return [1, 3].includes(type) == 1 ? `<span class="add-points">${points}+</span>` : `<span class="sub-points">-${points}</span>`;
}

const storePoints = (type) => {
    points_to_store.value.type = type;
    disabled.value = true;
    axios.post(route('userpoints.store'), {...points_to_store.value})
        .then((response) => {
            if (response.data?.errors) {
                errors.value = response.data.errors;
            }

            if (response.data?.success) {
                console.log(response.data);
                points_to_store.value = {...init_points_to_store};
                points_record_count.value += 1;
                max_page = Math.ceil(points_record_count.value / per_page);
                userPoints(1)

                user.value.user_profiles = response.data.user_profiles;
                emit('update:user', user.value);
                let msg = type == 3 ? 'Punkty zostały pomyślnie dodane do konta użytkownika.' : 'Punkty zostały pomyślnie odjęte od konta użytkownika.'
                useAlertStore.pushAlert('success', msg);
            }

            disabled.value = false;
        });
}

const disabled = ref(false);


</script>

<template>
    <div class="bg-gray-100 shadow-xl rounded p-6">
        <h2 class="font-semibold text-xl leading-tight">
            <i class="fa-solid fa-chart-simple text-orange"></i>
            Historia punktów
        </h2>

        <div class="text-left my-4">
            <div class="flex justify-start flex-col sm:flex-row md:flex-col gap-1 sm:gap-0 md:gap-1 lg:flex-row lg:gap-0">
                <input placeholder="wpisz ilość punktów..." type="text" class="py-1 border border-gray-300 px-4" v-model="points_to_store.value">
                <button :disabled="disabled" class="inline-flex items-center px-4 py-2 bg-transparent text-green-700 border border-green-700 font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-green-700 focus:outline-none transition ease-in-out duration-150" @click="storePoints(3)">Dodaj</button>
                <button :disabled="disabled" class="inline-flex items-center px-4 py-2 bg-transparent text-red-600 border border-red-700 font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-red-700 active:bg-red-900 focus:outline-none transition ease-in-out duration-150" @click="storePoints(4)">Odejmij</button>
                
            </div>
            <InputError class="mt-2" :message="errors.value ? errors.value[0] : ''" />

            <div class="flex justify-start mt-4">
                <label class="">
                    <Checkbox name="remember" v-model:checked="points_to_store.add_comment" />
                    <span class="ml-2 text-sm text-gray-600">Dodaj komentarz</span>
                </label>
            </div>
            
            
            
            <div v-if="points_to_store.add_comment" class="mt-4">                                
                <InputLabel value="Komentarz" :class="'text-left'" :text_color="'text-gray-600'"></InputLabel>
                <TextareaInput v-model="points_to_store.comment" :class="'mt-2'" placeholder="Komentarz..." :rows="3"></TextareaInput>
                <InputError class="mt-2" :message="errors.comment ? errors.comment[0] : ''" />
            </div>
            

        </div>
        
        <div class="flex mt-8" v-if="points_record_count > per_page">
            <nav class="flex justify-between">
                <a href="#"
                    class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200"
                    @click="current_page > 1 ? current_page-- : null">Wstecz</a>
                <a href="#"
                    class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 disabled-paginatio-item">{{
                        current_page }} z {{ max_page }}</a>
                <a href="#"
                    class="relative block py-2 px-3 leading-tight bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200"
                    @click="current_page < max_page ? current_page++ : null">Dalej</a>
            </nav>
        </div>

        <div class="table-container overflow-x-auto" v-if="points.length">
            <table class="text-center w-full border-collapse mt-4">
                <thead>
                    <tr class="table-tr">
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            #</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            Punkty</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            Dni</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            Auto</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            Komentarz</th>
                        <th
                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                            Data dodania</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-grey-lighter" v-for="(item, index) in points">
                        <td class="py-4 px-6 border-b border-grey-light">{{ index + 1 }}</td>
                        <td class="py-4 px-6 border-b border-grey-light" v-html="displayPoints(item.points, parseInt(item.type))"></td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ item.days }}</td>
                        <td class="py-4 px-6 border-b border-grey-light"><span v-html="isAuto(item)"></span>
                        </td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ item.comment ?? '-' }}</td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ formatDate(item.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex items-center justify-center spinner-container"
                :class="spinner_visible ? '' : 'hidden'">
                <div class="w-10 h-10 border-b-8 border-white-900 rounded-full animate-spin"></div>
            </div>
        </div>
        <AlertInfo v-else>Brak danych do wyświeltenia!</AlertInfo>

    </div>
</template>