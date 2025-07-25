import { defineStore } from "pinia";

export const use_maintenance_store = defineStore("maintenance_store", {
    state: () => {
        return {
            output: ''
        };
    },
    actions: {
        async up() {
            let response = await axios.post(route("artisan.up"));
            response = response.data;
            this.output = response.msg;
            window.location.href = window.location.origin + "/pulpit";
        },
        async down() {
            let response = await axios.post(route("artisan.down"));
            response = response.data;
            this.output = response.msg;

            window.location.href =
                window.location.origin +
                "/1630542a-246b-4b66-afa1-dd72a4c43515";
        },
    },
});
