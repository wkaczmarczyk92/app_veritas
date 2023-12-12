<script setup>

import { sprintf } from 'sprintf-js';

const props = defineProps({
    recruiter: {
        type: Object,
        required: true
    }
});

console.log(Object.keys(props.recruiter).length);

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
    return Object.keys(props.recruiter).length == 0 ? false : true;
}

</script>

<template>
    <section class="bg-gray-100 overflow-hidden shadow-xl rounded-lg px-6 sm:px-20 pt-16 pb-8 sm:pb-12 mt-10 relative">
        <!-- <img class="cbg-image" src="images/cbg.jpg" alt=""> -->
        <h2 class="text-2xl sm:text-3xl text-gray-800 font-bold relative z-10">
            Ważne informacje
        </h2>
        <div class="flex flex-col mt-8 relative z-10">
            <h2 class="text-md sm:text-xl">
                <i class="fa-solid fa-phone-volume"></i> <span class="text-gray-800">Telefon alarmowy:</span> <a class="text-blue-700 hover:text-blue-900 font-bold phone_link flex sm:inline" href="tel:+48535228007">+48-535-228-007</a> <span class="text-gray800">– czynny w godzinach</span> <span class="text-blue-700 font-bold">17:00 - 8:00</span>
            </h2>
            <div v-if="username_exists()">
                <h2 class="text-xl mt-8 text-gray-800">
                    Dane koordynatora: 
                </h2>
                <div class="text-lg ml-2 sm:ml-6 mt-2">
                    <i class="fa-solid fa-user-tie"></i> <span class="text-gray-800">{{ recruiter.usr_first_name }} {{ recruiter.usr_last_name }}</span>
                </div>
                <div class="text-lg ml-2 sm:ml-6 mt-1">
                    <i class="fa-solid fa-envelope"></i> <a class="text-blue-700 hover:text-blue-900 phone_link" :href="`mailto:${recruiter.usr_email}`">{{ recruiter.usr_email }}</a>
                </div>
                <div class="text-lg ml-2 sm:ml-6 mt-1" v-if="recruiter.usr_phone">
                    <i class="fa-regular fa-mobile"></i> <a class="text-blue-700 hover:text-blue-900 phone_link" :href="`tel:${recruiter.usr_phone}`">{{ display_phone_number(recruiter.usr_phone) }}</a>
                </div>
            </div>
        </div>
    </section>
</template>