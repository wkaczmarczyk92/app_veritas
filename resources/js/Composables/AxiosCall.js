import axios from "axios"

export async function _axios(method, route_name, params) {
    var response;

    switch (method) {
        case 'patch':
            response = await axios.patch(route(route_name), ...params)
            response = response.data
            break;
        case 'delete':
            response = await axios.delete(route(route_name, ...params))
            break;
        case 'post':
            break;
        default:
            throw new Error('Invalid method name.');
        
        return response;
    }
}