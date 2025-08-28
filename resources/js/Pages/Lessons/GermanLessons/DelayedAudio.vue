<!-- <script setup>
import { ref, onBeforeUnmount } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import Spinner from '@/Components/Forms/Spinner.vue'
import { useTestStore } from '@/Pinia/TestStore'

const props = defineProps({
    src: { type: String, required: true },
    delayMs: { type: Number, default: 1500 },
    controls: { type: Boolean, default: true }
})

const audioRef = ref(null)
const timer = ref(null)
const blocking = ref(false)
const alertStore = AlertStore()

const onPlay = (e) => {
    // jeżeli już wystartowaliśmy (po opóźnieniu), nie blokuj
    if (blocking.value) return

    e.preventDefault()
    const el = audioRef.value
    if (!el) return

    // zatrzymaj natychmiastowe odtwarzanie
    el.pause()
    if (timer.value) clearTimeout(timer.value)

    timer.value = setTimeout(async () => {
        try {
            blocking.value = true
            // niektóre przeglądarki (np. Safari) mogą nadal zablokować play() bez gestu
            await el.play()
        } catch (err) {
            // pokaż info, że trzeba kliknąć jeszcze raz (polityka autoplay)
            alertStore.set_alert?.('Przeglądarka zablokowała automatyczne odtwarzanie po opóźnieniu. Kliknij „Play” ponownie.', 'warning')
        } finally {
            blocking.value = false
        }
    }, props.delayMs)
}

onBeforeUnmount(() => {
    if (timer.value) clearTimeout(timer.value)
    if (audioRef.value) {
        audioRef.value.removeEventListener?.('play', onPlay)
    }
})

// --- NOWE: lokalny komponent opóźniający odtwarzanie

</script>
<template>
    <audio :controls="controls" ref="audioRef" @play="onPlay">
        <source :src="src" />
        Twoja przeglądarka nie wspiera plików audio.
    </audio>
</template> -->

<script setup>
import { ref, onBeforeUnmount } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

const props = defineProps({
    src: { type: String, required: true },
    delayMs: { type: Number, default: 1500 }
})

const audioRef = ref(null)
const timer = ref(null)
const blocking = ref(false)
const isPlaying = ref(false)
const alertStore = AlertStore()

const playWithDelay = () => {
    const el = audioRef.value
    if (!el) return
    if (timer.value) clearTimeout(timer.value)

    timer.value = setTimeout(async () => {
        try {
            blocking.value = true
            await el.play()
            isPlaying.value = true
        } catch (err) {
            alertStore.set_alert?.(
                'Przeglądarka zablokowała odtwarzanie po opóźnieniu. Kliknij „Play” ponownie.',
                'warning'
            )
        } finally {
            blocking.value = false
        }
    }, props.delayMs)
}

const togglePlay = () => {
    const el = audioRef.value
    if (!el || blocking.value) return
    if (isPlaying.value) {
        el.pause()
    } else {
        playWithDelay()
    }
}

const onEnded = () => {
    const el = audioRef.value
    isPlaying.value = false
    // reset na początek, żeby znów był „Play”
    if (el) el.currentTime = 0
}

const onPause = () => {
    // jeżeli użytkownik zatrzyma — przełącz ikonę na „Play”
    isPlaying.value = false
}

onBeforeUnmount(() => {
    if (timer.value) clearTimeout(timer.value)
})
</script>

<template>
    <div class="flex items-center gap-2">
        <button @click="togglePlay" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50"
            :disabled="blocking" aria-label="Play/Pause" type="button">
            <span v-if="!isPlaying"
                class="tw-flex tw-items-center tw-gap-2 tw-text-xl tw-text-blue-600 hover:tw-underline">
                <i class="fas fa-play"></i>
                Odtwórz audio
            </span>
            <span v-else class="tw-flex tw-items-center tw-gap-2 tw-text-xl tw-text-blue-600 hover:tw-underline">
                <i class="fas fa-play"></i>
                Zatrzymaj audio
            </span>
        </button>

        <!-- Ukryty element audio -->
        <audio ref="audioRef" :src="src" @ended="onEnded" @pause="onPause" style="display:none" />
    </div>
</template>
