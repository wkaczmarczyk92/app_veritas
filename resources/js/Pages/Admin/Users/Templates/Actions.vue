<script setup>

import { useUserStore } from '@/Pinia/UserStore';
import { use_auth_mimic_store } from '@/Pinia/Admin/AuthMimicStore';
import { ref, watch } from 'vue';
import { try_catch } from '@/Composables/TryCatch';
import Spinner from '@/Components/Forms/Spinner.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { use_processing_store } from '@/Pinia/ProcessingStore';
// import Spinner from '@/Composables/Spinner.vue';

const user_store = useUserStore();
const auth_mimic_store = use_auth_mimic_store();
const processing_store = use_processing_store();

const action = ref(null)
const alert_store = AlertStore();

watch(action, (value) => {
    console.log('action value', value)
})

const actions = [
    {
        'text': 'Aktywuj konto premium',
        'color': '#2563eb',
        'value': 'activate_premium',
    },
    {
        'text': 'Nalicz punkty od początku',
        'color': '#4b5563',
        'value': 'sync_points',
    },
]

const perform_action = async () => {
    console.log('Performing action:', action.value)
    await _actions[action.value]()
}

const _actions = {
    async sync_points() {
        processing_store.start();
        try {
            let response = await axios.post(route('sync.user.points', user_store.user.id));
            response = response.data

            console.log(response);

            alert_store.pushAlert(response);

            if (response.success) {
                user_store.user.user_profiles = response.user_profiles;
                user_store.user.user_points = response.user_points;
                action.value = null;
            }
        } catch (error) {
            console.log(error);
            alert_store.exception();
        } finally {
            processing_store.stop();
        }
    },
    async activate_premium() {
        let response = await user_store.set_premium_account(user_store.user.id);
        console.log('action response', response);
        if (response) {
            action.value = null;
        }
    }
}

// const sync_points = async () => {

// }

</script>

<template>
    <v-card>
        <v-card-text>
            <!-- <div class="tw-flex tw-flex-col tw-gap-2">
                <v-btn v-if="!user_store.user.user_profiles.crt_id_caretaker" color="#2563eb"
                    class="!tw-mr-auto tw-ml-1 tw-mt-10" @click="user_store.set_premium_account(user_store.user.id)">
                    Aktywuj konto premium
                </v-btn>
                <v-btn v-if="$page.props.god_mode" color="#a21caf" class="!tw-mr-auto tw-ml-1 tw-mt-1"
                    @click="auth_mimic_store.login_as_user(user_store.user.id)">
                    Zaloguj się jako użytkownik
                </v-btn>
            </div> -->
            <div class="tw-flex tw-flex-col tw-gap-2">
                <div>
                    <v-select density="comfortable" v-model="action" :items="actions" item-title="text"
                        variant="outlined" item-value="value">
                        <template v-slot:append>
                            <v-btn @click.stop.prevent="perform_action()" size="large" color="#16a34a" variant="flat">
                                Wykonaj
                            </v-btn>
                        </template>
                    </v-select>
                </div>
                <!-- <div>
                    <v-btn v-if="!user_store.user.user_profiles.crt_id_caretaker" color="#2563eb" class=""
                        @click="">
                        Aktywuj konto premium
                    </v-btn>
                </div> -->
                <!-- <div>
                    <v-btn v-if="$page.props.god_mode" color="#ea580c" class=""
                        @click="auth_mimic_store.login_as_user(user_store.user.id)">
                        Zaloguj się jako użytkownik
                    </v-btn>
                </div> -->
                <!-- <div>
                    <v-btn>
                        Nalicz punkty od początku
                    </v-btn>
                </div> -->
            </div>
        </v-card-text>
    </v-card>
</template>