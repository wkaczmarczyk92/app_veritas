import { defineStore } from 'pinia'

export const useModalStore = defineStore('modals', {
    state: () => (
        {
            active_modals: []
        }
    ),
    getters: {
        // get1
    },
    actions: {
        add(modal) {
            console.log(modal)
            let exists = this.active_modals.find((item) => {
                return item.name == modal.name
            })

            if (exists != 'undefined') {
                this.active_modals.push(modal)
            }

            console.log(this.active_modals)
        },
        active(searched) {
            let result = this.active_modals.filter((item) => {
                return item.name == searched
            })

            return result.length > 0
        },
        get() {
            return this.active_modals
        },
        close(name, index = null) {
            if (index != null) {
                let item = document.getElementById('modal_id_' + index)
                item.classList.add('hide-modal')
            }

            let founded_index = this.active_modals.findIndex((item) => {
                return item.name == name
            })

            if (founded_index != -1) {
                setTimeout(() => {
                    this.active_modals.splice(founded_index, 1)
                }, 200)
            }
        }
    },
})
