import { defineStore } from 'pinia';
import { shallowRef } from 'vue';
import NewAlertSuccess from '../Components/Alerts/NewAlertSuccess.vue';
import NewAlertDanger from '../Components/Alerts/NewAlertDanger.vue';

export const AlertStore = defineStore('alertStore', {
    state: () => {
        return {
            alerts: {

            },
            alert_types: {
                danger: shallowRef(NewAlertDanger),
                success: shallowRef(NewAlertSuccess)
            }
        }
    },
    actions: {
        pushAlert(name, msg) {
            let data = {
                msg: msg,
                component_name: shallowRef(this.alert_types[name])
            };

            let new_index = Object.keys(this.alerts).length > 0 ? Math.max(...Object.keys(this.alerts).map(Number)) + 1 : 0;
            this.alerts[new_index] = data;
        },
        remove(index) {
            delete this.alerts[index];
        }
    }
});