import http from "../../axiosHttp";

$(document).on("click", "#usersRoleBtn", function (e) {
    e.preventDefault();

    let name = $('#name').val();
    let menuPermissionsFirst = {};
    let menuPermissionsSecond = {};

    $(".menu1").each(function () {
        let trId = $(this).attr("id");
        let permissions = {};

        $(this)
            .find('td input[type="checkbox"]')
            .each(function () {
                let action = $(this).attr("class");
                let value = $(this).is(":checked") ? 1 : 0;
                permissions[action] = value;
            });

        menuPermissionsFirst[trId] = permissions;
    });

    $(".menu2").each(function () {
        let trId = $(this).attr("id");
        let permissions = {};

        $(this)
            .find('td input[type="checkbox"]')
            .each(function () {
                let action = $(this).attr("class");
                let value = $(this).is(":checked") ? 1 : 0;
                permissions[action] = value;
            });

            menuPermissionsSecond[trId] = permissions;
    });

    http.post("/perfis/inserir", { menuPermissionsFirst, menuPermissionsSecond, name })
        .then((response) => {
            noty("Sucesso", "Perfil inserido com sucesso.", "success");
            window.location = `${window.BASE_URL}/perfis`;
        })
        .catch((error) => {
            noty("Erro", "Não foi possível inserir o perfil.", "error");
        });
});

$(document).on("click", ".mark-all", function() {
    $(this).closest('tr').find('input[type="checkbox"]').prop('checked', this.checked);
});
