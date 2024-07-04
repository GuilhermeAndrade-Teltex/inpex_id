import http from "../../axiosHttp";

$(document).ready(function () {

    let permissions = $("#allowed_actions").val();
    permissions = JSON.parse(permissions);

    let create = '';
    let edit = '';
    let show = '';
    let destroy = '';

    $.each(permissions, function(index, value) {
        if (index == 'create') {
            create = value;
        }
        if (index == 'edit') {
            edit = value;
        }
        if (index == 'show') {
            show = value;
        }
        if (index == 'destroy') {
            destroy = value;
        }
    });

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
            {
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        ${show ? `<a href="/alunos/visualizar/${row.id}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>` : ''}
                        ${edit ? `<a href="/alunos/editar/${row.id}" class="btn btn-sm btn-dark"><i class="fa fa-pencil"></i></a>` : ''}
                        ${destroy ? `<button class="btn btn-sm btn-dark remove-student" data-student_id="${row.id}"><i class="fa fa-trash"></i></button>` : '' }

                    `;
                }
            }
        ],
        order: [[0, 'asc']],
        buttons: [
            {
                extend: 'print',
                text: 'Imprimir'
            },
            {
                extend: 'excel',
                text: 'Excel'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                customize: function (doc) {
                    var colCount = new Array();
                    $('#datatable-tabletools').find('tbody tr:first-child td').each(function () {
                        if ($(this).attr('colspan')) {
                            for (var i = 1; i <= $(this).attr('colspan'); $i++) {
                                colCount.push('*');
                            }
                        } else { colCount.push('*'); }
                    });
                    doc.content[1].table.widths = colCount;
                }
            }
        ],
        initComplete: function (settings, json) {
            $('<div />').addClass('dt-buttons mb-2 pb-1 text-end').prependTo('#datatable-tabletools_wrapper');

            $('#datatable-tabletools').DataTable().buttons().container().prependTo('#datatable-tabletools_wrapper .dt-buttons');

            $('#datatable-tabletools_wrapper').find('.btn-secondary').removeClass('btn-secondary').addClass('btn-default');
        }
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
