import http from "../../axiosHttp";

$(document).on("click", "#remove-student", function (e) {
    e.preventDefault();

    let studentId = $("#remove-student").data("student_id");

    http
        .delete(`/alunos/${studentId}`)
        .then((response) => {
            noty("Sucesso", "Aluno removido com sucesso.", "success");
            window.location.reload();
        })
        .catch((error) => {
            noty(
                "Erro",
                "Não foi possível excluir o aluno.",
                "error"
            );
        });
});
