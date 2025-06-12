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

const points_to_store = ref({ ...init_points_to_store });
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
    return item.auto == true ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>';
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
    errors.value = {};
    points_to_store.value.type = type;
    disabled.value = true;
    axios.post(route('userpoints.store'), { ...points_to_store.value })
        .then((response) => {
            if (response.data?.errors) {
                errors.value = response.data.errors;
            }

            if (response.data?.success) {
                console.log(response.data);
                points_to_store.value = { ...init_points_to_store };
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
    <v-card class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-p-6">
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
                <i class="fa-solid fa-chart-simple tw-text-orange-600"></i>
                <div>Historia punktów</div>
            </div>
        </template>
        <v-card-text>
            <v-alert color="info" v-if="points.length == 0">Brak danych do wyświeltenia!</v-alert>

            <div class="tw-text-left tw-my-4">
                <div class="tw-flex">
                    <div class="tw-min-w-[50%]">
                        <v-text-field variant="outlined" placeholder="Wpisz ilość punktów" density="compact"
                            v-model="points_to_store.value" :error-messages="errors.value ? errors.value[0] : ''">
                            <template v-slot:append>
                                <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
                                    <v-btn class="!tw-text-3xl !tw-p-0 !tw-m-0" color="#16a34a" @click="storePoints(3)"
                                        :disabled="disabled">
                                        +
                                    </v-btn>
                                    <v-btn color="#dc2626" class="!tw-text-3xl !tw-p-0 !tw-m-0" @click="storePoints(4)"
                                        :disabled="disabled">
                                        -
                                    </v-btn>
                                </div>
                            </template>
                        </v-text-field>
                    </div>

                </div>

                <v-checkbox label="Dodaj komentarz" v-model="points_to_store.add_comment"></v-checkbox>

                <Transition mode="out-in" name="fade">
                    <div v-if="points_to_store.add_comment">
                        <v-textarea v-model="points_to_store.comment" :rows="3" placeholder="Komentarz"
                            variant="outlined" label="Komentarz"
                            :error-messages="errors.comment ? errors.comment[0] : ''"></v-textarea>
                    </div>
                </Transition>

            </div>
            <div class="tw-flex tw-mt-8" v-if="points_record_count > per_page">
                <nav class="tw-flex tw-justify-between">
                    <a href="#"
                        class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                        @click="current_page > 1 ? current_page-- : null">Wstecz</a>
                    <a href="#"
                        class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 disabled-paginatio-item">{{
                            current_page }} z {{ max_page }}</a>
                    <a href="#"
                        class="tw-relative tw-block tw-py-2 tw-px-3 tw-leading-tight tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                        @click="current_page < max_page ? current_page++ : null">Dalej</a>
                </nav>
            </div>

            <div class="table-container tw-overflow-x-auto" v-if="points.length">
                <table class="tw-text-center tw-w-full tw-border-collapse tw-mt-4">
                    <thead>
                        <tr class="table-tr">
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                #</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Punkty</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Dni</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Auto</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Komentarz</th>
                            <th
                                class="tw-py-4 tw-px-6 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-sm tw-text-grey-dark tw-border-b tw-border-grey-light">
                                Data dodania</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:tw-bg-grey-lighter" v-for="(item, index) in points">
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ index + 1 }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light"
                                v-html="displayPoints(item.points, parseInt(item.type))"></td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ item.days }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light"><span
                                    v-html="isAuto(item)"></span>
                            </td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ item.comment ?? '-' }}</td>
                            <td class="tw-py-4 tw-px-6 tw-border-b tw-border-grey-light">{{ formatDate(item.created_at)
                            }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="tw-flex tw-items-center tw-justify-center spinner-container"
                    :class="spinner_visible ? '' : 'tw-hidden'">
                    <div class="tw-w-10 tw-h-10 tw-border-b-8 tw-border-white-900 tw-rounded-full tw-animate-spin">
                    </div>
                </div>
            </div>
        </v-card-text>

    </v-card>
</template>
