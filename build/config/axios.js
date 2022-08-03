import axios from "axios";
import router from "@/router";

window.axios = axios;
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;
axios.defaults.withCredentials = true;

axios.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token !== null) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  config.headers["Content-Type"] = "application/json";
  config.headers["x-frontloader"] = "x-frontloader";

  return config;
});

axios.interceptors.response.use(function (response) {
  return response;
}, function (error) {

  if (error.response.status === 401) {
    router.push("/login");
    return;
  }

  if (error.response.status) {
    router.push("/error");
    return;
  }

  return Promise.reject(error);
});
