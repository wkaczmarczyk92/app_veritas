<script>

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

export function date_of_last_update() {
    let date = new Date();
    date.setMonth(date.getMonth() - 1);
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    // let day = date.getDate();

    month = month < 10 ? '0' + month : month;
    // day = day < 10 ? '0' + day : day;

    return `${year}-${month}-10`;
}

export function date_of_next_update() {
    let date = new Date();
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    if (day >= 10) {
        month++;
        if (month > 12) {
            month = 1;
            year++;
        }
    }

    month = month < 10 ? '0' + month : month;

    return `${year}-${month}-10`;
}

</script>