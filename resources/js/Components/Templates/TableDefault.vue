<script setup> 

defineProps({
    headers: {
        type: [Object, Array],
        required: true
    }
});

</script>

<template>
    <div class="overflow-x-auto table-container">
        <table class="text-center w-full border-collapse">
            <thead>
                <tr class="table-tr">
                    <th v-for="header in headers"
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                        {{ header }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <slot></slot>
            </tbody>
        </table>
    </div>
</template>