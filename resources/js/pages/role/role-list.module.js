import http from "../../axiosHttp";

$(document).on("click", "#remove-role", function (e) {
    e.preventDefault();

    let roleId = $(this).data("role_id");

    http.delete(`/perfis/${roleId}`)
        .then((response) => {
            noty("Sucesso", "Perfil removido com sucesso.", "success");
            window.location.reload();
        })
        .catch((error) => {
            noty(
                "Erro",
                "Não foi possível excluir o perfil.",
                "error"
            );
        });
});
