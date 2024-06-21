import http from "../../axiosHttp";

$(document).on("click", "#remove-item", function (e) {
    e.preventDefault();

    let schoolId = $(this).data("school_id");

    http.delete(`/escolas/${schoolId}`)
        .then((response) => {
            noty("Sucesso", "Escola removida com sucesso.", "success");
            window.location.reload();
        })
        .catch((error) => {
            noty(
                "Erro",
                "Não é possível excluir uma escola que tenha alunos cadastrados.",
                "error"
            );
        });
});
