import http from "../../axiosHttp";

$(document).on("click", "#remove-client", function (e) {
    e.preventDefault();

    let clientId = $(this).data("client_id");

    http.delete(`/clientes/${clientId}`)
        .then((response) => {
            noty("Sucesso", "Cliente removido com sucesso.", "success");
            window.location.reload();
        })
        .catch((error) => {
            noty(
                "Erro",
                "Não é possível excluir um cliente que tenha escolas cadastradas.",
                "error"
            );
        });
});
