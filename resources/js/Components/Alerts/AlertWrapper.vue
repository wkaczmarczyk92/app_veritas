<script setup>

import { AlertStore } from '@/Pinia/AlertStore';

const useAlertStore = AlertStore();

</script>

<template>
    <div class="alert-wrapper z-50 w-[90%] sm:w-[75%] lg:w-[40%]">
        <TransitionGroup name="list" tag="ul">
            <li 
                v-for="(alert, index) in useAlertStore.alerts"
                :key="alert"
                class="text-xl"
            >
                <component 
                    :is="alert.component_name"
                    :index="parseInt(index)"
                    @remove="useAlertStore.remove"
                >{{ alert.msg }}</component>
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