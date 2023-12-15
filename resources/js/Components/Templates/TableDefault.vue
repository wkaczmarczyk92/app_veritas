<script setup> 

const props = defineProps({
    headers: {
        type: [Object, Array],
        required: true
    },
    font_size: {
        type: String,
        default: 'text-sm'
    }
});

const merge_class = (index) => {
    let class_name = ''
    if (index == 0) {
        class_name = 'pr-4'
    } else if (index == props.headers.length - 1) {
        class_name = 'pl-4'
    } else {
        class_name = 'px-6'
    
    }

    return class_name + ' ' + props.font_size
}

</script>

<template>
    <div class="overflow-x-auto table-container">
        <table class="text-center w-full border-collapse">
            <thead>
                <tr class="table-tr">
                    <th v-for="(header, index) in headers"
                        class="py-2 px-4 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light"
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