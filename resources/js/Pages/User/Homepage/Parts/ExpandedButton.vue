<script setup>

defineProps({
    icon: {
        type: [Boolean, String],
        default: false,
    },
    value: {
        type: String,
    },
    add_class: {
        type: String,
        default: ''
    },
    img: {
        type: String,
        default: ''
    },
    expanded_button: {
        type: Boolean,
        default: true
    }
})

</script>

<template>
    <button :class="add_class + (expanded_button ? ' expanded-button' : '')" class="">
        <span class="button-icon tw-text-white" v-if="icon">
            <i :class="icon"></i>
        </span>
        <img v-if="img != ''" :src="img" class="expanded-icon-img">
        <span class="button-text tw-text-white">
            {{ value ?? '' }}
        </span>
    </button>
</template>
