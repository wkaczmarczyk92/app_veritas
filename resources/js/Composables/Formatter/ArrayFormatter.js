export function to_array(value) {
    if (Array.isArray(value)) {
        return value;
    }

    if (typeof value === 'object') {
        return Object.values(value);
    }

    throw new Error('Value must be an array or an object');
}