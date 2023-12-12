import { defineStore  } from 'pinia';
import axios from 'axios';

export const defaultTable = defineStore('default_table', {
    state: () => {
        return {
            success_msg: '',
            success: false,
            route_options: {

            },
            route_name: ''
        }
    },
    actions: {
        async reload_request_by_url(url) {
            if (this.is_route_name) {
                let response = await axios.post(route(this.route_name));
                return response.data[0];
            }
        },
        async reload_requst_by_page_number(page) {
            if (this.is_options && this.is_route_name) {
                let response = await axios.post(route(this.route_name, this.options), {
                    page: page
                });
    
                return response.data[0];
            }
        },
        show_success_alert(msg) {
            this.success_msg = msg;
            this.success = true;

            setTimeout(() => {
                this.success_msg = '';
                this.success = false;
            }, 5000)
        },
        is_options() {
            if (this.options.length == 0) {
                throw new Error('Missing options parameter.');
            }

            return true;
        },
        is_route_name() {
            if (this.route_name.length == 0) {
                throw new Error('Missing route name parameter.');
            }

            return true;
        }
    }
});