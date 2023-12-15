<script setup>
import Header from '@/Templates/HTML/Header.vue';

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
    <Header
        title="Ostatnie zgłoszenia do BOK-u"
        route_name="bokrequest.index"
        route_title="Przejdź do zgłoszeń"
        icon="fa-solid fa-arrow-turn-down-right"
    ></Header>
    <div class="overflow-x-auto" v-if="latest_bok_request.length > 0">
        <table class="text-center w-full border-collapse text-xs">
            <thead>
                <tr class="table-tr">
                    <th
                        class="text-left py-2 pl-1 pr-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Opiekunka</th>
                    <th
                        class="py-2 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Kwota bonusu
                    </th>
                    <th
                        class="py-2 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        
                    </th>
                    <th
                        class="py-2 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light text-[10px]">
                        Data utworzenia
                    </th>
                </tr>
            </thead>
                <tbody>
                    <tr class="hover:bg-grey-lighter" v-for="(bok_request, index) in latest_bok_request">
                        <td class="py-3 pr-6 pl-1 border-b border-grey-light">{{ `${bok_request.user.user_profiles.first_name} ${bok_request.user.user_profiles.last_name}` }}</td>
                        <td class="py-3 px-6 border-b border-grey-light">{{ bok_request.subject.subject }}</td>
                        <td class="py-3 px-6 border-b border-grey-light">
                            <a class="edit-user" :href="`/uzytkownik/${bok_request.user.id}`">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                        </td>
                        <td class="py-4 pl-6 pr-1 border-b border-grey-light">{{ format(bok_request.created_at) }}</td>
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