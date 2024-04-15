<template>
    <!-- {{ question }} -->
    <div class="tw-mt-2 tw-text-base tw-px-2">
        <div v-for="(match, index) in question.matches" :key="index"
            class="tw-grid tw-grid-cols-2 tw-items-center odd:tw-bg-gray-200">
            <div class="tw-px-2">
                {{ match.match_text }}
            </div>
            <div class="tw-px-2">
                {{ match.answer }}
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({ question: Object })

</script>