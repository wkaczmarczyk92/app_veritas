<script setup>

import { ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import InputError from '@/Components/InputError.vue';

import TInput from '@/Composables/Form/TInput.vue';
import Button from '@/Composables/Buttons/Button.vue';

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
])

</script>

<template>
    <TInput label="PESEL" v-model:model_value="form.pesel" :disabled="form.btns.pesel.disabled"
        :error="form.errors && form.errors.pesel ? form.errors.pesel : ''" />

    <Button class="tw-mt-4" value="Uzyskaj kod SMS" :disabled="form.btns.pesel.disabled"
        @click="$emit('submit-sms-code')" />

    <TInput label="Kod SMS" class="tw-mt-10" :error="form.errors && form.errors.sms_code ? form.errors.sms_code : ''"
        v-model:model_value="form.sms_code" :disabled="form.btns.sms_password.disabled" />
    <Button class="tw-mt-4" value="Zmień hasło" color="orange" :disabled="form.btns.sms_password.disabled"
        @click="$emit('use-code')" />
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
