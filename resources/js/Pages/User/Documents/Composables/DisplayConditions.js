export function a1_completed(caretaker) {
    return (caretaker.assignments[0].contract.a1.one_start_date != null
        && caretaker.assignments[0].contract.a1.one_end_date != null
        && caretaker.assignments[0].contract.a1.one_submission_date != null
        && caretaker.assignments[0].contract.a1.one_receive_date != null)
}

export function ekuz_completed(caretaker) {
    return (caretaker.assignments[0].contract.a1.ekuz.ekz_start_date != null
        && caretaker.assignments[0].contract.a1.ekuz.ekz_end_date != null
        && caretaker.assignments[0].contract.a1.ekuz.ekz_receive_date != null
        && caretaker.assignments[0].contract.a1.ekuz.ekz_submission_date != null)
}

export function registration(caretaker) {
    return (caretaker.assignments[0].contract
        && caretaker.assignments[0].contract.registration)
}

export function is_registered(caretaker) {
    return (
        caretaker.assignments[0].contract.registration.reg_id_registration_status == 1
        && caretaker.assignments[0].ssg_canceled == 0
    )
}

export function is_unregistered(caretaker) {
    return (
        caretaker.assignments[0].contract == null
        || caretaker.assignments[0].contract.registration == null
        || caretaker.assignments[0].contract.registration.reg_unregistered_date != null
    )
    // return caretaker.assignments[0].contract.registration.reg_unregistered_date != null
}

export function contract_recive_date(caretaker) {
    return (
        caretaker.assignments[0].contract.con_receive_date != null
        && caretaker.assignments[0].contract.enclosure.enc_signed == 1
        && caretaker.assignments[0].contract.enclosure.enc_us_55 == 1
    )
        ? caretaker.assignments[0].contract.con_receive_date
        : 'brak';
}

export function a1_no_info(caretaker) {
    return (
        caretaker.assignments[0] == null
        || caretaker.assignments[0].contract == null
        || caretaker.assignments[0].contract.a1 == null
        || caretaker.assignments[0].contract.a1.one_submission_date == null
    )
}

export function a1_progress(caretaker) {
    return (
        caretaker.assignments[0].contract.con_receive_date != null
        && caretaker.assignments[0].contract.a1.one_submission_date != null
        && (
            caretaker.assignments[0].contract.a1.one_receive_date == null
            || caretaker.assignments[0].contract.a1.one_receive_date == '0000-00-00'
            || caretaker.assignments[0].contract.a1.one_start_date == null
            || caretaker.assignments[0].contract.a1.one_start_date == '0000-00-00'
            || caretaker.assignments[0].contract.a1.one_end_date == null
            || caretaker.assignments[0].contract.a1.one_end_date == '0000-00-00'
        )
    )
}

export function ekuz_no_info(caretaker) {
    return (
        caretaker.assignments[0].contract == null
        || caretaker.assignments[0].contract.a1 == null
        || caretaker.assignments[0].contract.a1.ekuz == null
        || caretaker.assignments[0].contract.a1.ekuz.ekz_submission_date == null
    )
}

export function ekuz_progress(caretaker) {
    return (
        caretaker.assignments[0].contract.a1.ekuz.ekz_submission_date != null
        &&
        (
            caretaker.assignments[0].contract.a1.ekuz.ekz_receive_date == null
            || caretaker.assignments[0].contract.a1.ekuz.ekz_receive_date == '0000-00-00'
            || caretaker.assignments[0].contract.a1.ekuz.ekz_start_date == null
            || caretaker.assignments[0].contract.a1.ekuz.ekz_start_date == '0000-00-00'
            || caretaker.assignments[0].contract.a1.ekuz.ekz_end_date == null
            || caretaker.assignments[0].contract.a1.ekuz.ekz_end_date == '0000-00-00'
        )
    )
}

export function has_business(caretaker) {
    return (
        caretaker.business != null
        && caretaker.business.bsn_id_business_type != null
        && [2, 3].includes(caretaker.business.bsn_id_business_type)
    )
}