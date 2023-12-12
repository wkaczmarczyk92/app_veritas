<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3';
import { AlertStore } from '@/Pinia/AlertStore';

const props = defineProps({
    next_page_url: {
        type: [String, null],
        required: true
    },
    prev_page_url: {
        type: [String, null],
        required: true
    },
    total: {
        type: Number,
        required: true
    },
    per_page: {
        type: Number,
        required: true
    },
    current_page: {
        type: Number,
        required: true
    },
    last_page: {
        type: Number,
        required: true
    },
    url_params_string: {
        type: String,
        default: ''
        // required: true
    },
    goto_page_btn: {
        type: String,
        default: '/uzytkownicy'
    },
    add_param: {
        type: String,
        default: ''
    },
    replace: {
        type: Array,
        default: []
    },
    page_name: {
        type: String,
        default: 'page'
    }
});

const emit = defineEmits([
    'show-alert'
])

const goto_page = ref(props.current_page);
const prev_page_url = ref(props.prev_page_url);
const next_page_url = ref(props.next_page_url);

const useAlertStore = AlertStore();

const goto = () => {
    if (goto_page.value > 0 && goto_page.value <= props.last_page && goto_page.value != props.current_page) {
        let url = '';
        
        url = `?${props.page_name}=${goto_page.value}` + props.url_params_string;
        const link = document.createElement('a');
        link.href = `${props.goto_page_btn}${url}`;
        link.click();
    } else {
        useAlertStore.pushAlert('danger', `Numer stony musi być inny niz aktualny, więszky od 0 oraz mniejszy lub równy ${props.last_page}!`);
    }
}

if (props.replace.length > 0) {
    if (prev_page_url.value != null) {
        prev_page_url.value = prev_page_url.value.replace(props.replace[0], props.replace[1]);
    }

    if (next_page_url.value != null) {
        next_page_url.value = next_page_url.value.replace(props.replace[0], props.replace[1]);
    }
}

</script>

<template>
    <div class="flex flex-col md:flex-row mb-4" v-if="total > per_page">
        <nav class="flex">
            <a :href="prev_page_url != null ? `${prev_page_url}${add_param}` : '#'" class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200" :class="prev_page_url == null ? 'hover:cursor-not-allowed' : ''">Wstecz</a>
            <a href="#" class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 disabled-paginatio-item">{{ current_page }} z {{ last_page }}</a>
            <a :href="next_page_url != null ? `${next_page_url}${add_param}` : '#'" class="shadow-md relative block py-2 px-3 bg-white border border-gray-300 text-gray-800 mr-1 hover:bg-gray-200" :class="next_page_url == null ? 'hover:cursor-not-allowed' : ''">Dalej</a>
        </nav>
        <div class="flex md:justify-center mt-3 md:mt-0">
            <div class="flex">
                <input v-model="goto_page" type="number" class="border-2 border-gray-400 py-2" placeholder="Numer strony">
                <button type="submit" class="btn-bg text-white font-bold py-2 px-4 ml-2" @click="goto()">Przejdź</button>
            </div>
        </div>
    </div>
</template>