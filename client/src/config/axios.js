import axios from "axios";
import router from "@/router";


axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;
axios.defaults.withCredentials = true;

axios.interceptors.request.use((config) => {
  const authToken = localStorage.getItem("token");

  if (authToken !== null) {
    config.headers.Authorization = `Bearer ${authToken}`;
  }

  config.headers["Content-Type"] = "application/json";

  return config;
});

axios.interceptors.response.use(function (response) {
  return response;
}, function (error) {

  if (error.response.status === 401) {
    router.push("/login");
    return;
  }

  if (error.response.status === 500) {
    router.push("/error");
    return;
  }

  return Promise.reject(error);
});
