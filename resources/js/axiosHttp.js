import axios from "axios";

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const baseURL = window.BASE_URL;
const http = axios.create({ baseURL });

export default http;
