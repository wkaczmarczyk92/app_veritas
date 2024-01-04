<script setup>
import { ref, watch } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';

const props = defineProps({
    pagination: {
        type: Object,
        required: true
    }
});

const goto_page = ref(props.pagination.current_page);
const useAlertStore = AlertStore();

console.log(props.pagination);

watch(() => props.pagination, (new_value, old_value) => {
    goto_page.value = new_value.current_page;
});

const emit = defineEmits([
    'show-alert',
    'reload-request-by-url',
    'reload-request-by-page-number'
]);


const goto = () => {
    if (goto_page.value > 0 && goto_page.value <= props.pagination.last_page && goto_page.value != props.pagination.current_page) {
        emit('reload-request-by-page-number', goto_page.value);
    } else {
        useAlertStore.pushAlert('danger', `Numer stony musi być inny niz aktualny, więszky od 0 oraz mniejszy lub równy ${props.pagination.last_page}!`);
    }
}

</script>

<template>
    <div class="tw-flex tw-flex-col md:tw-flex-row tw-mb-4" v-if="pagination.total > pagination.per_page">
        <nav class="tw-flex">
            <a @click.prevent="$emit('reload-request-by-url', pagination.prev_page_url)"
                class="tw-shadow-md tw-relative tw-block tw-py-2 tw-px-3 tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                :class="pagination.prev_page_url == null ? 'hover:cursor-not-allowed pointer-events-none' : 'hover:cursor-pointer'">Wstecz</a>
            <a href="#"
                class="tw-shadow-md tw-relative tw-block tw-py-2 tw-px-3 tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 disabled-paginatio-item">{{
                    pagination.current_page }} z {{ pagination.last_page }}</a>
            <a @click.prevent="$emit('reload-request-by-url', pagination.next_page_url)"
                class="tw-shadow-md tw-relative tw-block tw-py-2 tw-px-3 tw-bg-white tw-border tw-border-gray-300 tw-text-gray-800 tw-mr-1 hover:tw-bg-gray-200"
                :class="pagination.next_page_url == null ? 'hover:tw-cursor-not-allowed tw-pointer-events-none' : 'hover:tw-cursor-pointer'">Dalej</a>
        </nav>
        <div class="tw-flex md:tw-justify-center tw-mt-3 md:tw-mt-0">
            <div class="tw-flex">
                <input v-model="goto_page" type="number" class="tw-border-2 tw-border-gray-400 tw-py-2"
                    placeholder="Numer strony">
                <button type="submit" class="btn-bg tw-text-white tw-font-bold tw-py-2 tw-px-4 tw-ml-2"
                    @click="goto()">Przejdź</button>
            </div>
        </div>

    </div>
</template>
