<template>
    <section class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg tw-px-6 tw-py-14 sm:tw-px-20 tw-mt-10">
        <h2 class="tw-text-2xl sm:tw-text-3xl tw-text-gray-800 tw-font-bold tw-text-center">
            Pliki do pobrania
        </h2>
        <div class="tw-flex tw-justify-center">
            <hr class="tw-border-gray-400 tw-border-2 tw-mt-6 tw-w-1/2 sm:tw-w-1/3 ">
        </div>
        <!-- <div class="flex flex-row mt-10 sm:mt-16 text-lg sm:text-2xl font-bold justify-center">
            <i class="fa-solid fa-download mr-2 text-blue-700"></i>
            <div class="hover:cursor-pointer hover:underline hover:text-blue-700" @click="download('Ubezpieczenie-Generali.pdf')">Ubezpieczenie Generali</div>
        </div> -->
        <div class="tw-flex tw-flex-row tw-mt-10 sm:tw-mt-16 tw-text-lg sm:tw-text-2xl tw-font-bold tw-justify-center">
            <i class="fa-solid fa-download tw-mr-2 tw-text-blue-700"></i>
            <div class="hover:tw-cursor-pointer hover:tw-underline hover:tw-text-blue-700"
                @click="download('Ubezpieczenie-PZU.pdf')">Ubezpieczenie PZU</div>
        </div>
        <div class="tw-flex tw-flex-row tw-mt-10 sm:tw-mt-16 tw-text-lg sm:tw-text-2xl tw-font-bold tw-justify-center">
            <i class="fa-solid fa-download tw-mr-2 tw-text-blue-700"></i>
            <div class="hover:tw-cursor-pointer hover:tw-underline hover:tw-text-blue-700"
                @click="download('Referencje-wzor.pdf')">
                Referencje - wzór</div>
        </div>
    </section>
</template>

<script setup>

const download = (filename) => {
    axios.get(`/upload_files/${filename}`, { responseType: 'blob' })
        .then(response => {
            const blob = new Blob([response.data], { type: 'application/pdf' })
            const link = document.createElement('a')
            link.href = URL.createObjectURL(blob)
            link.download = filename
            link.click()
            URL.revokeObjectURL(link.href)
        }).catch(console.error)
}


</script>
