<script setup>

import Button from './Buttons/Button.vue';
import { ref } from 'vue'
import Processing from './Processing.vue';

const props = defineProps({
    type: {
        type: String,
        default: 'error'
    },
    with_cookies: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits([
    'close'
])

const dont_remind = ref(false)
const processing = ref(false)

const close_modal = async () => {
    processing.value = true
    if (dont_remind.value === true) {
        let response = await axios.post(route('cookie.set_alert_cookie'))
    }

    emit('close')
}
</script>

<template>
    <div id="modal"
        class="tw-fixed tw-inset-0 tw-overflow-y-auto tw-px-4 tw-py-6 sm:tw-px-0 tw-z-50 tw-bg-black tw-bg-opacity-50">
        <div class="tw-bg-white tw-rounded-lg tw-p-6 tw-w-full md:tw-w-4/5 lg:tw-w-1/2 tw-mx-auto tw-mt-20 tw-relative">
            <Processing v-if="processing" msg="Zamykanie..." />
            <v-alert class="" :icon="`$${type}`" :color="type">
                <slot></slot>
            </v-alert>
            <div class="tw-flex tw-flex-row tw-justify-between tw-items-center tw-mt-4">
                <v-checkbox v-model="dont_remind" label="Nie przypominaj przez jakiś czas"
                    class="!tw-flex"></v-checkbox>
                <Button class="" color="red" value="Zamknij" @click="close_modal()"></Button>
            </div>
        </div>

    </div>
</template>
