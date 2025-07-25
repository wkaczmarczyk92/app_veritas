<script setup>

import { ref } from 'vue';
import { format } from '@/Components/Functions/DateFormat.vue';
import DefaultDataGetter from '@/Components/Functions/DefaultDataGetter';

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: {}
    }
})


const default_data_getter = new DefaultDataGetter;
default_data_getter.route_name = 'load.bok.requests.for.user';
default_data_getter.options = { id: props.user.id };

const data = ref({});
const forms = ref({});

data.value = await default_data_getter.reload_requst_by_page_number(1);
forms.value = data.value.data;

const pagination_alert = ref({
    show: false,
    msg: ''
});

const headers = [
    {
        title: '#',
        value: 'id'
    },
    {
        title: 'Temat',
        value: 'subject.subject'
    },
    {
        title: 'Wiadomość',
        value: 'msg'
    },
    {
        title: 'Utworzono',
        value: 'created_at'
    }

]

</script>

<template>
    <div v-if="data && data.data && data.data.length <= 0" class="tw-p-4 tw-pt-0">
        <v-alert color="info" class="tw-mt-4">Brak zgłoszeń do BOK-u.</v-alert>
    </div>
    <div v-else>
        <v-data-table :items="forms" height="auto" item-value="id" :headers="headers">
            <template v-slot:item.created_at="{ value }">
                {{ format(value) }}
            </template>
        </v-data-table>
    </div>
</template>
