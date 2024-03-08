<script setup>

defineProps({
    icon: {
        type: [Boolean, String],
        default: false,
    },
    value: {
        type: String,
        required: true
    },
    add_class: {
        type: String,
        default: ''
    },
    img: {
        type: String,
        default: ''
    }
})

</script>

<template>
    <button :class="add_class" class="expanded-button">
        <span class="button-icon tw-text-white" v-if="icon">
            <i :class="icon"></i>
        </span>
        <img v-if="img != ''" :src="img" class="expanded-icon-img">
        <span class="button-text tw-text-white">
            {{ value }}
        </span>
    </button>
</template>
