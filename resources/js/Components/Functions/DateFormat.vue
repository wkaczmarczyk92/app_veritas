<script>

import axios from 'axios';

export function format(date) {
    if (date == '' || date == null || date == undefined) {
        return '';
    }

    if (typeof date != 'object') {
        if (typeof date == 'string') {
            const date_to_format = new Date(date);

            const year = date_to_format.getFullYear();
            const month = String(date_to_format.getMonth() + 1).padStart(2, '0');
            const day = String(date_to_format.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        return date;
    }

    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    day = day.toString().length == 1 ? `0${day}` : day;
    month = month.toString().length == 1 ? `0${month}` : month;

    return `${year}-${month}-${day}`;
}

export async function date_of_last_update() {
    let response = await axios.get(route('user.point.last.insert.date'))
    console.log(response)
    response = response.data
    return response.last_insert_date
}

export function date_of_next_update() {
    let date = new Date();
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    if (day >= 16) {
        month++;
        if (month > 12) {
            month = 1;
            year++;
        }
    }

    month = month < 10 ? '0' + month : month;

    return `${year}-${month}-16`;
}

export function format_range(date_range) {
    console.log(date_range);
    date_range.forEach((date, index) => {
        date_range[index] = format(date);
    })
    return date_range.join(' - ');
}

</script>