import { defineStore } from "pinia";
import { use_processing_store } from "../ProcessingStore";
import { AlertStore } from "../AlertStore";

export const use_users_store = defineStore("users_store", {
    state: () => {
        return {
            processing_store: use_processing_store(),
            alert_store: AlertStore(),
            users: [],
            filters: {
                search: "",
                order_by: "",
            },
        };
    },
    getters: {
        get_users() {
            console.log("search", this.filters.search);
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();

                return this.users.filter((user) => {
                    const match_full_name = (user.user_profiles.full_name || "")
                        .toLowerCase()
                        .includes(search);

                    const match_pesel = (user.user_profiles.pesel || "")
                        .toLowerCase()
                        .includes(search);

                    const match_email = (user.email || "")
                        .toLowerCase()
                        .includes(search);

                    const match_full_name_reversed = (
                        (user.user_profiles.last_name || "") +
                        " " +
                        (user.user_profiles.first_name || "")
                    )
                        .toLowerCase()
                        .includes(search);

                    const match_recruiter_full_name = (
                        (user.user_profiles.recruiter_first_name || "") +
                        " " +
                        (user.user_profiles.recruiter_last_name || "")
                    )
                        .toLowerCase()
                        .includes(search);

                    const match_recruiter_full_name_reversed = (
                        (user.user_profiles.recruiter_last_name || "") +
                        " " +
                        (user.user_profiles.recruiter_first_name || "")
                    )
                        .toLowerCase()
                        .includes(search);

                    return (
                        match_full_name ||
                        match_pesel ||
                        match_email ||
                        match_full_name_reversed ||
                        match_recruiter_full_name ||
                        match_recruiter_full_name_reversed
                    );
                });
            }

            return this.users;
        },
    },
    actions: {
        set_users(users) {
            this.users = users;
        },
        async search(search) {
            if (search == "" || search == null) {
                this.destroy_search();
            }

            this.processing_store.start();
            this.processing_store.set_msg("Szukanie użytkowników...");
            try {
                let response = await axios.post(
                    route("users.admin.search.index"),
                    {
                        filters: { search: search },
                    }
                );
                response = response.data;

                console.log(response);

                if (response.success) {
                    this.sync_users(response.users);
                    this.filters.search = search;
                }
            } catch (error) {
                console.log('users error', error);
                if (error?.response?.data?.msg) {
                    this.alert_store.danger(error.response.data.msg);
                }
            } finally {
                this.processing_store.stop();
            }
        },
        sync_users(new_users) {
            this.users = [...this.users, ...new_users];
            this.users = Array.from(
                new Map(this.users.map((user) => [user.id, user])).values()
            );
        },
        destroy_search() {
            this.filters.search = "";
            return;
        },
    },
});
