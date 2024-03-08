<script setup>

import { sprintf } from 'sprintf-js';

const props = defineProps({
    recruiter: {
        type: [Object, null],
        required: true
    }
});

console.log(props.recruiter);

const display_phone_number = (number) => {

    if (number.length == 11) {
        return sprintf('+%d%d-%d%d%d-%d%d%d-%d%d%d', ...number);
    }

    if (number.length == 9) {
        return sprintf('%d%d%d-%d%d%d-%d%d%d', ...number);
    }

    return number;
}

const username_exists = () => {
    return props.recruiter ? (Object.keys(props.recruiter).length == 0 ? false : true) : false;
}

</script>

<template>
    <section
        class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg tw-px-6 sm:tw-px-20 tw-pt-16 tw-pb-8 sm:tw-pb-12 tw-mt-10 tw-relative">
        <h2 class="tw-text-2xl sm:tw-text-3xl tw-text-gray-800 tw-font-bold tw-relative tw-z-10">
            Ważne informacje
        </h2>
        <div class="tw-flex tw-flex-col tw-mt-8 tw-relative tw-z-10">
            <h2 class="tw-text-md sm:tw-text-xl">
                <i class="fa-solid fa-phone-volume"></i> <span class="tw-text-gray-800">Telefon alarmowy:</span> <a
                    class="tw-text-blue-700 hover:tw-text-blue-900 tw-font-bold phone_link tw-flex sm:tw-inline"
                    href="tel:+717242989">+48 71 72 42 989</a> <span class="tw-text-gray-800">– czynny </span> <span
                    class="tw-text-blue-700 tw-font-bold">24/h</span>
            </h2>
            <div v-if="username_exists()">
                <h2 class="tw-text-xl tw-mt-8 tw-text-gray-800">
                    Dane koordynatora:
                </h2>
                <div class="tw-text-lg tw-ml-2 sm:tw-ml-6 tw-mt-2">
                    <i class="fa-solid fa-user-tie"></i> <span class="tw-text-gray-800">{{ recruiter?.usr_first_name }} {{
                        recruiter?.usr_last_name }}</span>
                </div>
                <div class="tw-text-lg tw-ml-2 sm:tw-ml-6 tw-mt-1">
                    <i class="fa-solid fa-envelope"></i> <a class="tw-text-blue-700 hover:tw-text-blue-900 phone_link"
                        :href="`mailto:${recruiter?.usr_email}`">{{ recruiter?.usr_email }}</a>
                </div>
                <div class="tw-text-lg tw-ml-2 sm:tw-ml-6 tw-mt-1" v-if="recruiter?.usr_phone">
                    <i class="fa-regular fa-mobile"></i> <a class="tw-text-blue-700 hover:tw-text-blue-900 phone_link"
                        :href="`tel:${recruiter?.usr_phone}`">{{ display_phone_number(recruiter?.usr_phone) }}</a>
                </div>
            </div>
        </div>
    </section>
</template>
