<script setup>
import NextPage from './NextPage.vue';
import PrevPage from './PrevPage.vue';
import DisplayCurrentPaginationPage from './DisplayCurrentPaginationPage.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { ref } from 'vue';

const props = defineProps({
    pagination: {
        type: Object,
        required: true
    },
    url_params_string: {
        type: String,
        default: ''
    },
    offset_name: {
        type: String,
        default: 'page'
    },
    page_name: {
        type: String,
        default: '/uzytkownicy'
    }
});

const goto_page = ref(props.pagination.current_page);
const useAlertStore = AlertStore();

const goto = () => {
    if (goto_page_ready()) {
        const link = document.createElement('a');
        link.href = createURL('goto');
        link.click();
    } else {
        useAlertStore.pushAlert('danger', `Numer stony musi być inny niz aktualny, więszky od 0 oraz mniejszy lub równy ${props.pagination.last_page}!`)
    }
}

const goto_page_ready = () => {
    return (goto_page.value > 0 &&
        goto_page.value <= props.pagination.last_page &&
        goto_page.value != props.pagination.current_page)
        ? true
        : false;
}

const createURL = (type = 'prev') => {
    switch (type) {
        case 'prev':
        default:
            return props.pagination.prev_page_url + props.url_params_string;
        case 'next':
            return props.pagination.next_page_url + props.url_params_string;
        case 'goto':
            return `${props.page_name}?${props.offset_name}=${goto_page.value}${props.url_params_string}`;
    }
}

</script>

<style>
#pagination-input {
    min-width: 150px !important;
}
</style>

<template>
    <div class="tw-flex tw-flex-col tw-justify-start tw-gap-2 tw-mb-4 tw-items"
        v-if="pagination.total > pagination.per_page">
        <nav class="tw-flex tw-gap-2">
            <PrevPage :url="createURL()"></PrevPage>
            <DisplayCurrentPaginationPage :current_page="pagination.current_page" :last_page="pagination.last_page">
            </DisplayCurrentPaginationPage>
            <NextPage :url="createURL('next')"></NextPage>
        </nav>
        <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
            <div>
                <v-text-field v-model="goto_page" label="numer strony" id="pagination-input" hide-details
                    class="tw-shadow-lg"></v-text-field>
            </div>
            <!-- <input v-model="goto_page" type="number" class="py-2 border-2 border-gray-400" placeholder="Numer strony"> -->
            <v-btn variant="tonal" @click="goto()" class="tw-shadow-lg" color="#22c55e">
                Przejdź
            </v-btn>
        </div>
    </div>
</template>


