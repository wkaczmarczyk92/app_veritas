<script setup>

import AlertInfo from '@/Components/Functions/AlertInfo.vue';
import { format } from '@/Components/Functions/DateFormat.vue';

defineProps({
    latest_payout_requests: {
        type: Object,
        default: {}
    }
})

</script>

<template>
    <h2 class="font-semibold text-xl leading-tight mb-3 flex flex-col sm:flex-row justify-between">
        <div>
            Ostatnie wnioski o wypłatę
        </div>                            
        <div v-if="latest_payout_requests.length > 0" class="mb-1">
            <a :href="route('payoutrequest.index')" class="text-sm underline hover:cursor-pointer text-blue-600">
                Przejdź do wniosków o wypłatę
                <i class="fa-solid fa-arrow-turn-down-right ml-1"></i>
            </a>
        </div>
    </h2>
    <div class="overflow-x-auto" v-if="latest_payout_requests.length > 0">
        <table class="text-center w-full border-collapse text-xs">
            <thead>
                <tr class="table-tr">
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Opiekunka</th>
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Kwota wypłaty
                    </th>
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        #
                    </th>
                    <th
                        class="py-4 px-6 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
                        Data utworzenia
                    </th>
                </tr>
            </thead>
                <tbody>
                    <tr class="hover:bg-grey-lighter" v-for="(payout_request, index) in latest_payout_requests">
                        <td class="py-4 px-6 border-b border-grey-light">{{ `${payout_request.user_has_bonus.user.user_profiles.first_name} ${payout_request.user_has_bonus.user.user_profiles.last_name}` }}</td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ payout_request.payout_value }}€</td>
                        <td class="py-4 px-6 border-b border-grey-light">
                            <a class="edit-user" :href="`/uzytkownik/${payout_request.user_has_bonus.user.id}`">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                        </td>
                        <td class="py-4 px-6 border-b border-grey-light">{{ format(payout_request.created_at) }}</td>
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