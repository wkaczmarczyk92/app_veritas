import { defineStore } from "pinia";
import axios from "axios";
import { use_processing_store } from "./ProcessingStore";
import { AlertStore } from "./AlertStore";

export const payoutRequestsStore = defineStore("new_payout_requests", {
    state: () => {
        return {
            count_incomplete_payout_request: 0,
            count_complete_payout_request: 0,
            count_for_approval_payout_request: 0,
            processing_store: use_processing_store(),
            alert_store: AlertStore(),
            payout_requests_in_progress: [],
            payout_requests_for_approval: [],
            payout_requests_completed: [],
        };
    },
    actions: {
        async countIncomplete() {
            let count = await axios.get(
                route("payout.requests.count.in.progress")
            );
            this.count_incomplete_payout_request = count.data;
        },
        async countForApproval() {
            let count = await axios.get(
                route("payout.requests.count.for.approval")
            );
            this.count_for_approval_payout_request = count.data;
        },
        async loadRequestsData({
            page = 1,
            page_name = "page",
            route_name = "payout.requests.get.in.progress",
            full_route = null,
        }) {
            let route_to_use =
                full_route != null
                    ? full_route
                    : route(route_name) + `?${page_name}=${page}`;
            let response = await axios.post(route_to_use);
            return response.data[0];
        },
        async load(bonus_status) {
            let response = await axios.post(
                route("payout.requests.get.in.progress", {
                    bonus_status: bonus_status,
                })
            );
            response = response.data;

            return response;
        },
        async change_status(ids, status_name) {
            this.processing_store.set_msg("Realizowanie wniosków");
            this.processing_store.start();

            try {
                let response = await axios.patch(
                    route("payout.requests.update.status"),
                    {
                        ids: ids,
                        status: status_name,
                    }
                );

                response = response.data;

                if (response.success) {
                    var reloaded_payout_requests = await this.load('in_progress');
                    this.payout_requests_in_progress = reloaded_payout_requests[0];

                    reloaded_payout_requests = await this.load('for_approval');
                    this.payout_requests_for_approval = reloaded_payout_requests[0];

                    reloaded_payout_requests = await this.load('completed');
                    this.payout_requests_completed = reloaded_payout_requests[0];
                    // payout_requests.value = reloaded_payout_requests[0];
                }

                this.alert_store.pushAlert(response);
            } catch (error) {
                console.log(error);
                this.alert_store.exception();
            } finally {
                this.processing_store.stop();
            }

            console.log('nowe payouty:', reloaded_payout_requests)

            return reloaded_payout_requests[0] ?? [];
        },
    },
});
