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
    <h3 class="tw-font-semibold tw-text-md md:tw-text-lg tw-leading-tight tw-text-left tw-mt-6">
        Zmień hasło z konsultantem
    </h3>
    <div class="tw-text-left tw-mb-10">
        <span @click="$emit('change-view', null)"
            class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline tw-text-sm md:tw-text-md">
            Wróć
        </span>
    </div>
    <div class="tw-flex tw-flex-col">
        <div class="tw-text-left">
            <InputLabel for="pesel" value="PESEL" />

            <TextInput id="pesel" type="text" class="tw-mt-1 tw-block tw-w-full" v-model="form.pesel" required
                @change="$emit('update:form', form)" />

            <InputError class="tw-mt-2" :message="form.errors && form.errors.pesel ? form.errors.pesel : ''" />

            <MButton add_class="tw-mt-4" value="Chcę zmienić hasło" bg="tw-bg-rose-600" hover="hover:tw-bg-rose-700"
                @click="$emit('new-password-request')" :disabled="form.btns.pesel.disabled"></MButton>
        </div>
    </div>
</template>
