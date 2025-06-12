<script setup>

import { ref } from 'vue';
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
    'submit-new-password'
])

</script>

<template>
    <TInput label="Hasło" v-model:model_value="form.password" type="password"
        :error="form.errors && form.errors.password ? form.errors.password[0] : ''" />

    <TInput label="Powtórz hasło" class="tw-mt-4" v-model:model_value="form.password_confirmation" type="password"
        :error="form.errors && form.errors.password_confirmation ? form.errors.password_confirmation[0] : ''" />

    <Button class="tw-mt-4" value="Zmień hasło" :disabled="form.btns.password_form.disabled"
        @click="$emit('submit-new-password')" />
</template>
