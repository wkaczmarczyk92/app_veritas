<template>
    <v-card v-for="(question, index) in questions" :key="index" :title="question.question"
        class="quesiton-card odd:!tw-bg-gray-200 !tw-border odd:tw-border-gray-200 even:tw-border-white hover:tw-border-gray-300 hover:tw-shadow-gray-400 hover:tw-shadow-lg">
        <template v-slot:prepend>
            <span class="tw-text-xl">{{ question.order }}.</span>
        </template>
        <template v-slot:subtitle>
            <div class="tw-flex tw-flex-row tw-flex-wrap tw-gap-1">
                <QuestionTypeChip :question="question" />
                <MatchChip v-if="question.type.type == 'match'" :question="question" />
                <AnswerChip v-if="question.type.type == 'closed' || question.type.type == 'closed_multiple'"
                    :question="question" />
                <CorrectAnswerChip v-if="question.type.type == 'closed_multiple'" :question="question" />
            </div>


        </template>
        <v-card-text>
            <!-- <QuestionOpenDisplay v-if="question.type.type == 'open'" :question="question" /> -->
            <QuestionClosedDisplay v-if="question.type.type == 'closed'" :question="question" />
            <QuestionClosedMultipleDisplay v-if="question.type.type == 'closed_multiple'" :question="question" />
            <QuestionMatchDisplay v-if="question.type.type == 'match'" :question="question" />
            <!-- {{ question }} -->
        </v-card-text>
    </v-card>
</template>

<script setup>

import QuestionTypeChip from '@/Composables/Chips/QuestionTypeChip.vue';
import MatchChip from '@/Composables/Chips/MatchChip.vue';
import AnswerChip from '@/Composables/Chips/AnswerChip.vue';
import CorrectAnswerChip from '@/Composables/Chips/CorrectAnswerChip.vue';

import QuestionOpenDisplay from './QuestionOpenDisplay.vue';
import QuestionClosedDisplay from './QuestionClosedDisplay.vue';
import QuestionClosedMultipleDisplay from './QuestionClosedMultipleDisplay.vue';
import QuestionMatchDisplay from './QuestionMatchDisplay.vue';

const props = defineProps({
    questions: Object
})

</script>
<style>
.quesiton-card .v-card-subtitle {
    opacity: 1 !important;
}
</style>