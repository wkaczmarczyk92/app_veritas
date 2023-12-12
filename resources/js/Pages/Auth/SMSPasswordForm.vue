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
    'submit-sms-code',
    'use-code',
    'change-view'
])

</script>

<template>
    <InputLabel for="pesel" value="PESEL" />
    <TextInput id="pesel" type="text" class="mt-1 block w-full" v-model="form.pesel" required :disabled="form.btns.pesel.disabled" @change="$emit('update:form', form)"/>
    <InputError class="mt-2" :message="form.errors && form.errors.pesel ? form.errors.pesel : ''" />

    <MButton add_class="mt-4" value="Uzyskaj kod SMS" bg="bg-green-600" hover="hover:bg-green-700"
        @click="$emit('submit-sms-code')" :disabled="form.btns.pesel.disabled"></MButton>

    <InputLabel for="sms" value="Kod SMS" class="mt-10"/>
    <TextInput id="sms" type="text" class="mt-1 block w-full" v-model="form.sms_code" required :disabled="form.btns.sms_password.disabled" @change="$emit('update:form', form)"/>
    <InputError class="mt-2" :message="form.errors && form.errors.sms_code ? form.errors.sms_code : ''" />

    <MButton add_class="mt-4 disabled:opacity-50" value="Zmień hasło" bg="bg-amber-600" :hover="form.btns.sms_password.disabled ? '' : 'hover:bg-amber-700'"
        @click="$emit('use-code')" :disabled="form.btns.sms_password.disabled"></MButton>

</template>

<style>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}

</style>
