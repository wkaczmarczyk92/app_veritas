<template>
    <v-text-field v-model="model_copy" @input="$emit('update:model_value', $event.target.value)" type="text" required
        variant="outlined" :error-messages="error ? error : null" :clearable="clearable"
        :hide-details="error ? false : true" @click:clear="$emit('update:model_value', '')" :readonly="readonly">
        <template v-for="(slot, name) in $slots" v-slot:[name]="slotData">
            <slot :name="name" v-bind="slotData"></slot>
        </template>
    </v-text-field>
</template>

<script setup>

import { ref, watch } from 'vue'

const props = defineProps({
    model_value: {
        type: [String, null, Date, Number, undefined],
        required: true
    },
    error: {
        type: [String, null, undefined],
        required: true
    },
    clearable: {
        type: Boolean,
        default: true
    },
    readonly: {
        type: Boolean,
        default: false
    },
    callback: {
        type: [null, Function],
        default: null
    }
})

watch(() => props.error, (new_error) => {
    console.log(new_error)
});

const get_model_value = () => {
    if (props.callback != null) {
        return props.callback(props.model_value)
    } else {
        return props.model_value
    }
}

const model_copy = ref(get_model_value())

defineEmits(['update:model_value'])

watch(() => props.model_value, (new_value) => {
    if (props.callback != null) {
        model_copy.value = props.callback(new_value);
    } else {
        model_copy.value = new_value;
    }
});

</script>