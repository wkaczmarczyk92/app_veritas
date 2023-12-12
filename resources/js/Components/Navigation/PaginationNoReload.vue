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
    <div class="flex flex-col md:flex-row mb-4" v-if="pagination.total > pagination.per_page">
        <nav class="flex">
            <a @click.prevent="$emit('reload-request-by-url', pagination.prev_page_url)" class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200" :class="pagination.prev_page_url == null ? 'hover:cursor-not-allowed pointer-events-none' : 'hover:cursor-pointer'">Wstecz</a>
            <a href="#" class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 disabled-paginatio-item">{{ pagination.current_page }} z {{ pagination.last_page }}</a>
            <a @click.prevent="$emit('reload-request-by-url', pagination.next_page_url)" class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200" :class="pagination.next_page_url == null ? 'hover:cursor-not-allowed pointer-events-none' : 'hover:cursor-pointer'">Dalej</a>
        </nav>
        <div class="flex md:justify-center mt-3 md:mt-0">
            <div class="flex">
                <input v-model="goto_page" type="number" class="border-2 border-gray-400 py-2" placeholder="Numer strony">
                <button type="submit" class="btn-bg text-white font-bold py-2 px-4 ml-2" @click="goto()">Przejdź</button>
            </div>
        </div>

    </div>
</template>