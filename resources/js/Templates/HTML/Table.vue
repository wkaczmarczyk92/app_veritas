<template>
    <table class="w-full text-xs text-center border-collapse">
        <thead>
            <tr>
                <th :class="class_th" v-for="(header, index) in headers" :key="index">
                    {{ header }}
                </th>
            </tr>
        </thead>
        <tbody>
            <slot></slot>
        </tbody>
    </table>
</template>

<script setup>

defineProps({
    headers: {
        type: Array,
        required: true
    }
})

const class_th = 'py-1 text-gray-200 uppercase bg-blue-800 border-b border-grey-light'

</script>

<style></style>
