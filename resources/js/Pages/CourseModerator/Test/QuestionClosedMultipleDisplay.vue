<template>
    <div class="tw-px-4 tw-pt-2">
        <v-checkbox v-for="(choice, index) in question.closed_choices" hide-details class="" :label="choice.choice"
            :model-value="choice.is_correct ? true : false" :class="choice.is_correct ? 'tw-text-green-600' : ''"
            disabled />
    </div>
</template>

<script setup>

import { computed } from 'vue'

const props = defineProps({
    question: Object
})

// const get_model = () => {
//     console.log('question index correct', props.question.closed_choices.findIndex(choice => choice.is_correct == 1))
//     return props.question.closed_choices.findIndex(choice => choice.is_correct == 1)
// }

// const correct_index = get_model()

</script>

<style>
.v-selection-control--density-default {
    --v-selection-control-size: 30px;
}

.v-input--density-default {
    --v-input-control-height: 30px;
}
</style>