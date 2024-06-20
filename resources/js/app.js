import "./bootstrap";
import "./theme";
import "./theme.init";

import "./app_components";

import axios from "axios";
import Alpine from "alpinejs";

window.Alpine = Alpine;
window.Axios = axios;
window.BASE_URL = document
    .querySelector('meta[name="url-base"]')
    .getAttribute("content");

Alpine.start();
