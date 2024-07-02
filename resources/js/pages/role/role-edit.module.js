import http from "../../axiosHttp";

$(document).on("click", "#usersRoleBtn", function (e) {
    e.preventDefault();

    let profile_id = $("#id_profile").val();
    let name = $("#name").val();
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

        permissions["permission_id"] = $(this).find(".idFirstPermission").val();

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
        permissions["permission_id"] = $(this).find(".idFirstPermission").val();

        menuPermissionsSecond[trId] = permissions;
    });

    http.put(`/perfis/editar/${profile_id}`, {
        menuPermissionsFirst,
        menuPermissionsSecond,
        name,
    })
        .then((response) => {
            noty("Sucesso", "Perfil editado com sucesso.", "success");
            window.location = `${window.BASE_URL}/perfis`;
        })
        .catch((error) => {
            noty("Erro", "Não foi possível editar o perfil.", "error");
        });
});

$(document).on("click", ".mark-all", function () {
    $(this)
        .closest("tr")
        .find('input[type="checkbox"]')
        .prop("checked", this.checked);
});
