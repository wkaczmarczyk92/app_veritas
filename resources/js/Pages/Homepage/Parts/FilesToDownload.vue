<template>
    <section class="bg-gray-100 overflow-hidden shadow-xl rounded-lg px-6 py-14 sm:px-20 mt-10">
        <h2 class="text-2xl sm:text-3xl text-gray-800 font-bold text-center">
            Pliki do pobrania
        </h2>
        <div class="flex justify-center">
            <hr class="border-gray-400 border-2 mt-6 w-1/2 sm:w-1/3 ">
        </div>
        <!-- <div class="flex flex-row mt-10 sm:mt-16 text-lg sm:text-2xl font-bold justify-center">
            <i class="fa-solid fa-download mr-2 text-blue-700"></i>
            <div class="hover:cursor-pointer hover:underline hover:text-blue-700" @click="download('Ubezpieczenie-Generali.pdf')">Ubezpieczenie Generali</div>
        </div> -->
        <div class="flex flex-row mt-10 sm:mt-16 text-lg sm:text-2xl font-bold justify-center">
            <i class="fa-solid fa-download mr-2 text-blue-700"></i>
            <div class="hover:cursor-pointer hover:underline hover:text-blue-700" @click="download('Ubezpieczenie-PZU.pdf')">Ubezpieczenie PZU</div>
        </div>
        <div class="flex flex-row mt-10 sm:mt-16 text-lg sm:text-2xl font-bold justify-center">
            <i class="fa-solid fa-download mr-2 text-blue-700"></i>
            <div class="hover:cursor-pointer hover:underline hover:text-blue-700" @click="download('Referencje-wzor.pdf')">Referencje - wzór</div>
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