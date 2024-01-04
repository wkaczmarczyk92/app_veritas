<script setup>

const props = defineProps({
    headers: {
        type: [Object, Array],
        required: true
    },
    font_size: {
        type: String,
        default: 'tw-text-sm'
    }
});

const merge_class = (index) => {
    let class_name = ''
    if (index == 0) {
        class_name = 'tw-pr-4'
    } else if (index == props.headers.length - 1) {
        class_name = 'tw-pl-4'
    } else {
        class_name = 'tw-px-6'

    }

    return class_name + ' ' + props.font_size
}

</script>

<template>
    <div class="tw-overflow-x-auto tw-table-container">
        <table class="tw-text-center tw-w-full tw-border-collapse">
            <thead>
                <tr class="table-tr">
                    <th v-for="(header, index) in headers"
                        class="tw-py-2 tw-px-4 tw-bg-grey-lightest tw-font-bold tw-uppercase tw-text-grey-dark tw-border-b tw-border-grey-light"
                        :class="merge_class(index)">
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
