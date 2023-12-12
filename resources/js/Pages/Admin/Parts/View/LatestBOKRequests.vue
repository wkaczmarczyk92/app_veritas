<script setup>

import { format } from '@/Components/Functions/DateFormat.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

defineProps({
    latest_bok_request: {
        type: Object,
        default: {}
    }
})

</script>

<template>
    <h2 class="font-semibold text-xl leading-tight mt-14 flex flex-col sm:flex-row justify-between mb-2">
        <div>
            Ostatnie zgłoszenia do BOK-u
        </div>                            
        <div v-if="latest_bok_request.length > 0" class="mb-1">
            <a :href="route('bokrequest.index')" class="text-sm underline hover:cursor-pointer text-blue-600">
                Przejdź do zgłoszeń
                <i class="fa-solid fa-arrow-turn-down-right ml-1"></i>
            </a>
        </div>
    </h2>
    <div class="overflow-x-auto" v-if="latest_bok_request.length > 0">
        <table class="text-center w-full border-collapse text-xs">
            <thead>
                <tr class="table-tr">
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Opiekunka</th>
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Kwota bonusu
                    </th>
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        
                    </th>
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Data utworzenia
                    </th>
                </tr>
            </thead>
                <tbody>
                    <tr class="hover:bg-grey-lighter" v-for="(bok_request, index) in latest_bok_request">
                        <td class="py-4 px-6 border-b border-grey-light">{{ `${bok_request.user.user_profiles.first_name} ${bok_request.user.user_profiles.last_name}` }}</td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ bok_request.subject.subject }}</td>
                        <td class="py-4 px-6 border-b border-grey-light">
                            <a class="edit-user" :href="`/uzytkownik/${bok_request.user.id}`">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                        </td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ format(bok_request.created_at) }}</td>
                    </tr>
                </tbody>
        </table>
    </div>
    <div v-else>
        <AlertInfo
            title=""
            :show_icon="false">
            Brak nowych wniosków o wypłatę
        </AlertInfo>
    </div>
</template>