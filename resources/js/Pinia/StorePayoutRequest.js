import { defineStore } from 'pinia';
import axios from 'axios';

export const payoutRequestsStore = defineStore('new_payout_requests', {
    state: () => {
        return {
            count_incomplete_payout_request: 0,
            count_complete_payout_request: 0,
            count_for_approval_payout_request: 0,
        }
    },
    actions: {
        async countIncomplete() {
            let count = await axios.get(route('payout.count.incomplete'));
            this.count_incomplete_payout_request = count.data;
        },
        async countForApproval() {
            let count = await axios.get(route('payout.count.for.approval'));
            this.count_for_approval_payout_request = count.data;
        },
        async loadRequestsData({ page = 1, page_name = 'page', route_name = 'load.incomplete.payout.requests', full_route = null }) {
            let route_to_use = full_route != null ? full_route : route(route_name) + `?${page_name}=${page}`;
            let response = await axios.post(route_to_use);
            return response.data[0];
        }
    }
});