<script setup>

import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import InputError from '@/Components/InputError.vue';


const props = defineProps({
    form: {
        type: Object,
        required: true
    }
});

const form = ref(props.form);

const emits = defineEmits([
    'update:form',
    'new-password-request',
    'change-view'
])

console.log(form.value);

</script>

<template>
    <h3 class="font-semibold text-md md:text-lg leading-tight text-left mt-6">
        Zmień hasło z konsultantem
    </h3>
    <div class="text-left mb-10">
        <span @click="$emit('change-view', null)" class="text-blue-600 hover:cursor-pointer hover:text-blue-600 hover:underline text-sm md:text-md">
            Wróć
        </span>    
    </div>
    <div class="flex flex-col">
        <div class="text-left">
            <InputLabel for="pesel" value="PESEL" />

            <TextInput id="pesel" type="text" class="mt-1 block w-full" v-model="form.pesel" required @change="$emit('update:form', form)"/>

            <InputError class="mt-2" :message="form.errors && form.errors.pesel ? form.errors.pesel : ''" />

            <MButton add_class="mt-4" value="Chcę zmienić hasło" bg="bg-rose-600" hover="hover:bg-rose-700"
                @click="$emit('new-password-request')" :disabled="form.btns.pesel.disabled"></MButton>
        </div>
    </div>
</template>