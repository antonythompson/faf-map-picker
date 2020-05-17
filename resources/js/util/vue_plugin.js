import LaravelRouter from "../thirdparty/Ziggy/route";
import {laravelRoute} from "./common";
let MyPlugin = {
    install: function (Vue, options) {
        Vue.prototype.$loggedInUser = window.loggedInUser;
        Vue.prototype.$laravelRouter = function (name, args = [], abs = true) {
            let route = null;
            try{
                route =  LaravelRouter(name, args, abs);
            } catch(e) {
                console.warn(e);
            }
            return route;
        };
        Vue.prototype.$laravelRoute = laravelRoute;
    }
};
export default MyPlugin;