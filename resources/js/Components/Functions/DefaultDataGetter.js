export default class DefaultDataGetter {
    success_msg = '';
    success = false;
    route_options = {

    };
    route_name = '';

    reload_request_by_url = async (url) => {
        console.log(url);
        if (this.is_route_name()) {
            let response = await axios.post(url);
            return response.data[0];
        }
    }

    reload_requst_by_page_number = async (page) => {
        if (this.is_options() && this.is_route_name()) {
            let response = await axios.post(route(this.route_name, this.options), {
                page: page
            });

            return response.data[0];
        }
    }

    is_options() {
        if (this.options.length == 0) {
            throw new Error('Missing options parameter.');
        }

        return true;
    }

    is_route_name() {
        if (this.route_name.length == 0) {
            throw new Error('Missing route name parameter.');
        }

        return true;
    }
}