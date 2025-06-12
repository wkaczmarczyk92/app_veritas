<template>
    <div class="tw-fixed tw-h-full tw-w-full loader-bg tw-z-[10000]">
        <div
            class="tw-flex tw-items-center tw-justify-center tw-fixed tw-top-[50%] tw-left-[50%] -tw-translate-y-[50%] -tw-translate-x-[50%] tw tw-z-40">
            <div
                class="tw-inline-flex tw-items-center tw-font-semibold tw-leading-6 tw-text-xl tw-text-gray-200 tw-transition tw-ease-in-out tw-duration-150">
                <svg class="tw-animate-spin -tw-ml-1 tw-mr-3 tw-h-6 tw-w-6 tw-text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="tw-text-blue-600" viewBox="0 0 24 24">
                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                    <path class="tw-opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Ładowanie danych...
            </div>
        </div>
    </div>
</template>

<style>
.loader-bg:before {
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    background-color: #1f2937;
    opacity: .9;
}
</style>