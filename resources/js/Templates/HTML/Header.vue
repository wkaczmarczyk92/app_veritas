<template>
    <div class="font-semibold text-xl leading-tight flex flex-col sm:flex-row justify-between mb-4">
        <div>
            {{ title }}
        </div>
        <div class="mb-1" v-if="second_part()">
            <a :href="route(route_name ?? '-')" class="text-blue-600 underline text-sm">
                {{ route_title}}
                <i v-if="icon != null" :class="icon + ' ml-1'"></i>
            </a>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    title: {
        type: String,
        default: 'Domyślny tytuł'
    },
    route_name: {
        type: [String, null],
        default: null
    },
    route_title: {
        type: [String, null],
        default: null
    },
    icon: {
        type: [String, null],
        default: null
    }
})

const second_part = () => {
    if (props.route_name != null && props.route_title != null) {
        return true;
    }

    return false;
}

</script>