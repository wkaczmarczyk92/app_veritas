import { defineStore } from 'pinia';

export const use_processing_store = defineStore('processing_store', {
    state: () => {
        return {
            state: false,
            progress_value: 0,
            with_progress_value: false,
            msg: ''
        }
    },
    actions: {
        start() {
            this.state = true;
            this.progress_value = 0;
        },
        stop() {
            this.state = false;
        },
        start_progress_value() {
            this.with_progress_value = true
        },
        set_msg(msg) {
            this.msg = msg
        }
    }
});
