import { defineStore } from 'pinia';

export const useModalStore = defineStore('modalStore', {
    state: () => {
        return {
            visibility: {
                bok: false,
                contact_form: false,
                ready_to_departure: false,
                points_history: false,
                profile_image: false,
                family_recommendations: false,
                caretaker_recommendations: false
            }
        }
    }
});
