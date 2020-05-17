import axios from 'axios';
import Router from '../thirdparty/Ziggy/route.js';

const apiCall = async (route_name, method, route_args, args) => {
    let url = Router(route_name, route_args, true);
    let params = null;
    let data = null;
    if (method === 'get') {
        params = args;
    } else {
        data = args;
    }

    let response = await axios.request({
        url,
        method,
        data,
        params,
    });

    return response.data;
}
export default {
    //generic methods
    get(resource, args, route_args = []) {
        return apiCall(`api.${resource}`, 'get', route_args, args)
    },
    post(resource, args, route_args) {
        return apiCall(`api.${resource}`, 'post', route_args, args)
    },

    //resource methods
    index(resource, args, route_args = []) {
        return apiCall(`api.${resource}.index`, 'get', route_args, args)
    },
    store(resource, args, route_args = []) {
        return apiCall(`api.${resource}.store`, 'post', route_args, args)
    },
    show(resource, args, route_args) {
        return apiCall(`api.${resource}.show`, 'get', route_args, args)
    },
    update(resource, args, route_args) {
        return apiCall(`api.${resource}.update`, 'put', route_args, args)
    },
    destroy(resource, route_args) {
        return apiCall(`api.${resource}.destroy`, 'delete', route_args, {})
    },
}