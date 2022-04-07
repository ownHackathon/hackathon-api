import axios from "axios";
import router from "@/router";

window.axios = axios;
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;

axios.interceptors.request.use((config) => {
    config.headers["Content-Type"] = "application/json";
    config.headers["x-frontloader"] = "x-frontloader";

    return config;
});

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {

    if (!error.response.status) {
        router.push("/error");
        return;
    }

    if (error.response.status === 401) {
        router.push("/login");
        return;
    }

    return Promise.reject(error);
});
