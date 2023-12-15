<script setup>
import Header from '@/Templates/HTML/Header.vue';

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
    <Header
        title="Ostatnie wnioski o wypłatę"
        route_name="payoutrequest.index"
        route_title="Zobacz wszystkie"
        icon="fa-solid fa-arrow-turn-down-right"
    ></Header>
    <div class="overflow-x-auto" v-if="latest_payout_requests.length > 0">
        <table class="text-center w-full border-collapse text-xs">
            <thead>
                <tr class="table-tr">
                    <th
                        class="py-4 pr-6 pl-1 bg-grey-lightest font-bold uppercase text-grey-dark border-b border-grey-light">
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
                        <td class="py-3 pr-6 pl-1 border-b border-grey-light">{{ `${payout_request.user_has_bonus.user.user_profiles.first_name} ${payout_request.user_has_bonus.user.user_profiles.last_name}` }}</td>
                        <td class="py-3 px-6 border-b border-grey-light">{{ payout_request.payout_value }}€</td>
                        <td class="py-3 px-6 border-b border-grey-light">
                            <a class="edit-user" :href="`/uzytkownik/${payout_request.user_has_bonus.user.id}`">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                        </td>
                        <td class="py-3 pl-6 pr-1 border-b border-grey-light">{{ format(payout_request.created_at) }}</td>
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