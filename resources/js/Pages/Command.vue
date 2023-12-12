<template>
    <Head title="VeritasApp - konsola" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Konsola</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col gap-4 bg-gray-200 px-6 py-4">
                    <label for="">Artisan:</label>
                    <input type="text" v-model="artisan_call">
                    <button class="bg-green-700 hover:bg-green-800 w-fit px-4 text-gray-200" @click="artisanCall()">execute</button>
                    <p class="">
                        <span>output:</span>
                        {{ output.artisan }}
                    </p>
                </div>
                <div class="flex flex-col gap-4 bg-gray-200 px-6 py-4 mt-20">
                    <label for="">Przerwa(1630542a-246b-4b66-afa1-dd72a4c43515):</label>
                    <button class="bg-green-700 hover:bg-green-800 w-fit px-4 text-gray-200" @click="down()">down</button>
                    <button class="bg-green-700 hover:bg-green-800 w-fit px-4 text-gray-200" @click="up()">up</button>
                    <p>
                        <span>output:</span>
                        {{ output.maintenance }}
                    </p>
                </div>
                <div class="flex flex-col gap-4 mt-20 bg-gray-200 px-6 py-4">
                    <label for="">Call class data:</label>
                    <input type="text" v-model="class_call">
                    <button class="bg-green-700 hover:bg-green-800 w-fit px-4 text-gray-200" @click="classCall()">execute</button>
                    <p>
                        <span>output:</span>
                        {{ output.callback }}
                        <br>
                        {{ output.exception }}
                    </p>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import axios from 'axios';

const output = ref({
    artisan: '',
    callback: '',
    exception: '',
    maintenance: ''
})

const class_call = ref('');
const artisan_call = ref('');

// const command_type = ref(null);

const up = async () => {
    let response = await axios.post(route('artisan.up'));
    response = response.data
    output.value.maintenance = response.msg
}

const down = async () => {
    let response = await axios.post(route('artisan.down'));
    response = response.data
    output.value.maintenance = response.msg
}

const artisanCall = async () => {
    console.log(1);
    if (artisan_call.value != '') {
        let response = await axios.post(route('command.call'), {
            command_type: 'artisan',
            artisan_call: artisan_call.value
        })

        response = response.data;
        output.value.artisan = response.msg;
    }
}

const classCall = async () => {
    console.log(class_call.value);
    if (class_call.value != '') {
        let response = await axios.post(route('command.call'), {
            command_type: 'class_call',
            class_call: class_call.value
        })

        response = response.data;
        output.value.callback = response.msg;
        output.value.exception = response.exception;
    }
}



</script>