<script setup>

import { AlertStore } from '@/Pinia/AlertStore';

const useAlertStore = AlertStore();

</script>

<template>
    <div class="alert-wrapper tw-z-50 tw-w-[90%] sm:tw-w-[75%] lg:tw-w-[40%]">
        <TransitionGroup name="list" tag="ul">
            <li v-for="(alert, index) in useAlertStore.alerts" :key="alert" class="tw-text-xl">
                <component :is="alert.component_name" :index="parseInt(index)" @remove="useAlertStore.remove">{{ alert.msg
                }}</component>
            </li>

        </TransitionGroup>
    </div>
</template>

<style>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
