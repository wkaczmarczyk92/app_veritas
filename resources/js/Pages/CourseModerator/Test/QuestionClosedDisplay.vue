<template>
    <div class="tw-px-4 tw-pt-2">
        <v-radio-group class="" v-model="correct_index" hide-details disabled>
            <v-radio v-for="(choice, index) in question.closed_choices"
                :class="correct_index == index ? 'tw-text-green-600' : ''" :value="index" :label="choice.choice">
            </v-radio>
        </v-radio-group>
    </div>
</template>

<script setup>

// import { computed } from 'vue'

const props = defineProps({
    question: Object
})

const get_model = () => {
    console.log('question index correct', props.question.closed_choices.findIndex(choice => choice.is_correct == 1))
    return props.question.closed_choices.findIndex(choice => choice.is_correct == 1)
}

const correct_index = get_model()

</script>

<style>
.v-selection-control--density-default {
    --v-selection-control-size: 30px;
}
</style>