<script setup>

const props =  defineProps({
    href: {
        type: String,
        required: true
    },
    text: {
        type: String,
        required: false
    },
    blank: {
        type: Boolean,
        default: false
    }
})

const target = () => {
    return props.blank ? '_blank' : '_self';
}

</script>

<template>
    <a :href="href" class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-underline hover:tw-text-blue-800" :target="target()">
        <slot v-if="$slots.default"></slot>
        <span v-else>{{ text }}</span>
    </a>
</template>