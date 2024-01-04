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
    'submit-new-password'
])

</script>

<template>
    <InputLabel for="password" value="Hasło" />
    <TextInput id="password" type="password" class="tw-mt-1 tw-block tw-w-full" v-model="form.password" required
        @change="$emit('update:form', form)" />
    <InputError class="tw-mt-2" :message="form.errors && form.errors.password ? form.errors.password[0] : ''" />

    <InputLabel for="password_confirmation" value="Powtórz hasło" class="mt-10" />
    <TextInput id="password_confirmation" type="password" class="tw-mt-1 tw-block tw-w-full"
        v-model="form.password_confirmation" required />
    <InputError class="tw-mt-2"
        :message="form.errors && form.errors.password_confirmation ? form.errors.password_confirmation[0] : ''" />

    <MButton add_class="tw-mt-4 disabled:tw-opacity-50" value="Zmień hasło" bg="tw-bg-amber-600"
        :hover="form.btns.password.disabled ? '' : 'hover:tw-bg-amber-700'" @click="$emit('submit-new-password')"></MButton>
</template>
