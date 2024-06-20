import http from "../axiosHttp";

http.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$(document).on("click", "button#btn_logout", function (e) {
    e.preventDefault();

    http.post('/logout').then(response => {
        if (response.data.status === 'SUCCESS') {
            window.location = `${window.BASE_URL}/login`;
        }
    }).catch(error => {
        console.error("There was an error logging out:", error);
    });
});
