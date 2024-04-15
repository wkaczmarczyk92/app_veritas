export function username(user) {
    let username = ''

    if (user.user_profiles && user.user_profiles.first_name) {
        username += user.user_profiles.first_name
    }

    if (user.user_profiles && user.user_profiles.last_name) {
        username += ' ' + user.user_profiles.last_name
    }

    return username != '' ? username : 'brak danych'
}