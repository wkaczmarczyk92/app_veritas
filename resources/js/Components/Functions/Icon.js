export default class Icon {

    bonus_payout_pending = '<i class="fa-light fa-hourglass-start text-xl text-orange-600"></i>';
    bonus_payout_completed = '<i class="fa-solid fa-circle-check text-xl text-green-600"></i>';

    bonus_payout = (completed) => {
        if (completed) {
            return this.bonus_payout_completed;
        } else {
            return this.bonus_payout_pending;
        }
    }

    ready_to_payout_dark = '<i class="fa-solid fa-alarm-exclamation text-xl"></i>';
    ready_to_payout_light = '<i class="fa-solid fa-alarm-exclamation text-xl"></i>';

    _rpt = (completed) =>  {
        if (completed) {
            return this.ready_to_payout_dark;
        } else {
            return this.ready_to_payout_light;
        }
    }

    _rpt_class = (completed, ready_to_payout) => {
        if (ready_to_payout) {
            if (completed) {
                return 'text-green-700';
            } else {
                return 'text-red-500 ready-to-payout';
            }
        } else {
            return 'text-red-200';
        }
    }

    ok = '<i class="fa-solid fa-circle-check text-xl"></i>';
    not_ok = '<i class="fa-solid fa-circle-xmark text-xl"></i>';

    _is_ok = (ok) => {
        if (ok) {
            return this.ok;
        } else {
            return this.not_ok;
        }
    }

    lock_close = '<i class="fa-solid fa-lock-keyhole text-indigo-700 text-xl"></i>';
    lock_open = '<i class="fa-solid fa-lock-open text-indigo-300 text-xl"></i>';

    lock = (lock) => {
        console.log(lock);
        return !!lock ? this.lock_close : this.lock_open;
    }



}