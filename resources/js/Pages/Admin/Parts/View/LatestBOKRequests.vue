<script setup>

import { format } from '@/Components/Functions/DateFormat.vue';
import AlertInfo from '@/Components/Functions/AlertInfo.vue';

defineProps({
    latest_bok_request: {
        type: Object,
        default: {}
    }
})

const headers = [
    'Opiekunka',
    'Kwota bonusu',
    '',
    'Data utworzenia'
]

</script>

<template>
    <v-card variant="tonal" :color="'white'" class="tw-mt-6 tw-shadow-2xl">
        <template #title>
            <div class="tw-flex tw-flex-row tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-text-lg tw-font-bold tw-text-gray-800">
                    Ostatnie zgłoszenia do BOK-u
                </div>
                <a :href="route('bokrequest.index')"
                    class="tw-text-sm tw-text-blue-500 hover:tw-underline hover:tw-text-blue-700">
                    Przejdź do zgłoszeń
                    <span>
                        <i class="fa-solid fa-arrow-turn-down-right"></i>
                    </span>
                </a>
            </div>

        </template>
        <v-card-text>
            <div class="overflow-x-auto" v-if="latest_bok_request.length > 0">
                <v-data-table :items="latest_bok_request" height="auto" item-value="id">
                    <template #headers>
                        <tr class="tw-bg-gray-200">
                            <th v-for="(header, index) in headers" :key="index">
                                <span class="tw-font-bold">{{ header }}</span>
                            </th>
                        </tr>
                    </template>
                    <template v-slot:item="{ item }">
                        <tr class="tw-text-xs">
                            <td>{{ `${item.user.user_profiles.first_name}
                                                            ${item.user.user_profiles.last_name}` }}</td>
                            <td>{{ item.subject.subject }}</td>
                            <td class="tw-text-lg">
                                <a class="edit-user" :href="`/uzytkownik/${item.user.id}`">
                                    <i class="tw-text-blue-500 fa-solid fa-user-pen hover:tw-text-blue-700"></i>
                                </a>
                            </td>
                            <td>{{ format(item.created_at) }}</td>
                        </tr>
                    </template>
                    <template #bottom></template>
                </v-data-table>
            </div>
            <AlertInfo v-else title="" :show_icon="false">
                Brak nowych wniosków o wypłatę
            </AlertInfo>
        </v-card-text>
    </v-card>
</template>
