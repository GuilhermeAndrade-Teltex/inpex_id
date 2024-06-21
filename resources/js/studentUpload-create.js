import Dropzone from "dropzone";

// --------------------------- DROPZONE --------------------------------- //
let count = 0;

Dropzone.autoDiscover = false;
let myDropzone = new Dropzone(".dropzone", {
    paramName: "file",
    acceptedFiles: ".jpeg,.jpg,.png",
    uploadMultiple: true,
    autoProcessQueue: false,
    maxFilesize: 100,
    parallelUploads: 100,
    maxFiles: 100,
    addRemoveLinks: true,
    dictDefaultMessage: "Arraste os arquivos aqui",
    dictFallbackMessage: "Seu browser não é suportado",
    dictInvalidFileType:
        "O formato do arquivo é inválido. \nArquivos aceitos (.jpeg,.jpg,.png).",
    dictFileTooBig: "O arquivo excedeu o tamanho permitido",
    dictResponseError: "Ocorreu um erro no servidor",
    dictUploadCanceled: "Upload foi cancelado",
    dictCancelUpload: "Cancelar",
    dictCancelUploadConfirmation: "Deseja cancelar o envio do arquivo?",
    dictMaxFilesExceeded: "Não é possível enviar mais arquivos",
    dictRemoveFile: "Remover",
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    addedfiles: function (file) {
        if (
            $("#class").val() != "" &&
            $("#school_id").val() != null &&
            $(".dz-preview").length > 0
        ) {
            $("#saveImageButton")
                .removeClass("disabled")
                .prop("disabled", false);
            $(".dz-default").hide();
        }
        if (
            $(".dz-preview").length > 0 &&
            ($("#school_id").val() == null || $("#class").val() == "")
        ) {
            $(".dz-default").hide();
        }
        if (
            $("#class").val() != "" &&
            /^[^A-Za-z0-9]/.test($("#class").val().charAt(0)) &&
            $("#school_id").val() != null &&
            $(".dz-preview").length > 0
        ) {
            $("#saveImageButton")
                .removeClass("disabled")
                .prop("disabled", true);
        }
    },
    reset: function (files) {
        if ($(".dz-preview").length == 0) {
            $("#saveImageButton")
                .removeClass("disabled")
                .prop("disabled", true);
        }
        $(".dz-default").show();
    },
    sending: function (file, xhr, formData) {
        $("#saveImageButton").addClass("disabled").prop("disabled", true);
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
    },
    success: function (file, response) {
        $("#saveImageButton").removeClass("disabled").prop("disabled", false);
        response = JSON.parse(response);
        if (count === 0) {
            createTable(response);
            count = 1;
        }
        myDropzone.removeAllFiles();

        let error = 0;
        $.each(response, function (key, value) {
            if (value["cod"] == "error") {
                error++;

            }
        });

        if (error == 0) {
            noty(
                "Sucesso",
                "Todos os alunos foram cadastrados com sucesso.",
                "success"
            );
        } else {
            noty(
                "Erro",
                "Não foi possível cadastrar alguns alunos",
                "error"
            );
        }
    },
    error: function (file, response) {
        if (file.accepted == false) {
            noty("Arquivo Inválido", response, "error");
            myDropzone.removeFile(file);
            $(".dz-default").show();
        } else {
            noty(
                response.message,
                "Não foi possível salvar os alunos.",
                "error"
            );

            spinner_hide();
        }
    },
});

$(document)
    .off("click", "#saveImageButton")
    .on("click", "#saveImageButton", function (e) {
        e.preventDefault();
        if (myDropzone) {
            myDropzone.processQueue();
            count = 0;
            spinner_show();
        } else {
            console.error("Dropzone instance not found");
        }
    });

if ($("#class").val() == "" || $("#school_id").val() == null) {
    $("#saveImageButton").addClass("disabled").prop("disabled", true);
}

$("#class").on("change", function (e) {
    if (
        $("#class").val() != "" &&
        $("#school_id").val() != null &&
        $(".dz-preview").length >= 1 &&
        !/^[^A-Za-z0-9]/.test($("#class").val().charAt(0))
    ) {
        $("#saveImageButton").removeClass("disabled").prop("disabled", false);
    } else {
        $("#saveImageButton").addClass("disabled").prop("disabled", true);
    }
});

$("#school_id").on("change", function (e) {
    if (
        $("#class").val() != "" &&
        $("#school_id").val() != null &&
        !/^[^A-Za-z0-9]/.test($("#class").val().charAt(0)) &&
        $(".dz-preview").length >= 1
    ) {
        $("#saveImageButton").removeClass("disabled").prop("disabled", false);
    }
});

function createTable(response) {
    let html = `
            <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Escola</th>
                        <th>Turma</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="preview_uploaded_photos">
                    ${createPreview(response)}
                </tbody>
            </table>`;

    // Destrua o DataTables se ele já estiver inicializado
    if ($.fn.DataTable.isDataTable("#datatable-tabletools")) {
        $("#datatable-tabletools").DataTable().destroy();
    }

    // Insira a tabela no DOM
    $("#datatable-container").html(html);

    // Inicialize o DataTables novamente
    $("#datatable-tabletools").DataTable({
        dom:
            "<'row'<'col-md-12 d-flex justify-content-end 'B>>" +
            "<'row'<'col-md-6'l><'col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: "print",
                text: "Imprimir",
                className: "btn btn-default",
            },
            {
                extend: "excel",
                text: "Excel",
                className: "btn btn-default",
            },
        ],
        buttonsContainer: '<div class="btn-group"></div>',
    });

    $(".btn-group .btn").removeClass("btn-secondary");

    $("#datatable-tabletools_wrapper").css({
        display: "flex",
        "flex-direction": "column",
        gap: "1vh",
    });

    // Remova a classe hidden para mostrar a tabela
    $("#card_images").removeClass("hidden");
    spinner_hide();
}

function createPreview(response) {
    let html = "";
    $.each(response, function (key, value) {
        html += `
                <tr class="${key}">
                    <td class="student_photo" style="display:flex"><img src="${value["image"]}" class="img_upload"></td>
                    <td class="student_name" style="width: 150px;max-width: 150px">${value["name"]}</td>
                    <td class="student_school">${value["school"]}</td>
                    <td class="student_class">${value["class"]}</td>
                    <td class="student_status">${value["status"]}</td>
                </tr>`;
    });

    return html;
}
// --------------------------- DROPZONE --------------------------------- //
