import http from "../../axiosHttp";

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#datatable-tabletools')) {
        $('#datatable-tabletools').DataTable().destroy();
    }

    $('#datatable-tabletools').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: $('#datatable-tabletools').data('url'),
            type: 'GET',
            data: function (d) {
                d.length = d.length || 10;
                d.start = d.start || 0;
                d.order = d.order || [{ column: 0, dir: 'asc' }];
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            {
                data: 'created_at',
                name: 'created_at',
                render: function (data, type, row) {
                    if (type === 'display' && data) {
                        var date = new Date(data);
                        return date.getDate().toString().padStart(2, '0') + '/' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
                            date.getFullYear() + ' ' +
                            date.getHours().toString().padStart(2, '0') + ':' +
                            date.getMinutes().toString().padStart(2, '0') + ':' +
                            date.getSeconds().toString().padStart(2, '0');
                    }
                    return data;
                }
            },
            { data: 'name', name: 'name' },
            { data: 'cpf', name: 'cpf' },
        ],
        order: [[0, 'asc']]
    });

    $(document).on("click", ".remove-student", function (e) {
        e.preventDefault();
        let studentId = $(this).data("student_id");
        if (confirm('Tem certeza que deseja remover este aluno?')) {
            http.delete(`/alunos/${studentId}`)
                .then((response) => {
                    noty("Sucesso", "Aluno removido com sucesso.", "success");
                    $('#datatable-tabletools').DataTable().ajax.reload();
                })
                .catch((error) => {
                    noty("Erro", "Não foi possível excluir o aluno.", "error");
                });
        }
    });
});
