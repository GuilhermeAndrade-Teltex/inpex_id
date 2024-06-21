import http from "../../axiosHttp";

$(document).on("click", "#remove-user", function (e) {
    e.preventDefault();

    let clientId = $(this).data("user_id");

    http.delete(`/usuarios/${clientId}`)
        .then((response) => {
            noty("Sucesso", "Usuário removido com sucesso.", "success");
            window.location.reload();
        })
        .catch((error) => {
            noty(
                "Erro",
                "Não foi possível excluir o usuário.",
                "error"
            );
        });
});
