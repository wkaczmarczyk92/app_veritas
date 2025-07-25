<script>

export function level(levels, user_level) {
    return levels.filter((value, index) => {
        return Number(value.id) == Number(user_level);
    })[0].name;
}

export function levelColor(level) {
    let color = '';

    switch (parseInt(level)) {
        case 1:
            color = 'text-green';
            break;
        case 2:
            color = 'text-brown';
            break;
        case 3:
            color = 'text-silver';
            break;
        case 4:
            color = 'text-gold';
            break;
    }

    return color;
}

export function neonColor(level) {
    let color = '';

    switch (parseInt(level)) {
        case 1:
            color = 'black-neon';
            break;
        case 2:
            color = 'brown-neon';
            break;
        case 3:
            color = 'silver-neon';
            break;
        case 4:
            color = 'gold-neon';
            break;
    }

    return color;
}

</script>