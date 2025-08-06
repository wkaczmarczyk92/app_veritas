<script setup>

import { useUserStore } from '@/Pinia/UserStore';
import { use_auth_mimic_store } from '@/Pinia/Admin/AuthMimicStore';

const user_store = useUserStore();
const auth_mimic_store = use_auth_mimic_store();

</script>

<template>
    <v-card>
        <v-card-text>
            <div class="tw-flex tw-flex-col tw-gap-2">
                <v-btn v-if="!user_store.user.user_profiles.crt_id_caretaker" color="#2563eb"
                    class="!tw-mr-auto tw-ml-1 tw-mt-10" @click="user_store.set_premium_account(user_store.user.id)">
                    Aktywuj konto premium
                </v-btn>
                <v-btn v-if="$page.props.god_mode" color="#a21caf" class="!tw-mr-auto tw-ml-1 tw-mt-1"
                    @click="auth_mimic_store.login_as_user(user_store.user.id)">
                    Zaloguj się jako użytkownik
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>