$(document).ready(function () {
    // Select the element with ID 'cpf'
    var cpfElement = $("#cpf");

    // Format the text content of 'cpfElement' using the 'formatarCPF' function
    cpfElement.text(formatarCPF(cpfElement.text()));

    function mascaraCEP(event) {
        let input = event.target;
        let value = input.value.replace(/\D/g, ""); // Remove caracteres não numéricos
        value = value.slice(0, 8); // Limita a 8 dígitos

        if (value.length > 5) {
            value = value.slice(0, 5) + "-" + value.slice(5);
        }

        input.value = value;
    }

    $("#cep").on("input", mascaraCEP);

    // Function to format CPF
    function formatarCPF(cpf) {
        cpf = cpf.replace(/\D/g, ""); // Remove non-digit characters from CPF
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"); // Format CPF with dots and dash
    }

    $("#cep").blur(function () {
        // Listener para quando o campo CEP perde o foco
        var cep = $(this).val().replace(/\D/g, ""); // Remove caracteres não numéricos
        if (cep.length === 8) {
            // Verifica se o CEP tem 8 dígitos
            $.getJSON(
                "https://viacep.com.br/ws/" + cep + "/json/",
                function (dados) {
                    if (!("erro" in dados)) {
                        $("#address").val(dados.logradouro);
                        $("#district").val(dados.bairro);
                        $("#city").val(dados.localidade);
                        $("#state").val(dados.uf);
                    } else {
                        // CEP não encontrado, limpe os campos ou mostre uma mensagem de erro
                        $("#address").val("");
                        $("#district").val("");
                        $("#city").val("");
                        $("#state").val("");
                        noty("", "CEP não encontrado.", "warning");
                    }
                }
            );
        }
    });

    // Event listener for blur on elements with class 'real-time-validation'
    $(".real-time-validation")
        .find(".form-control")
        .blur(function () {
            var field = $(this);
            var fieldName = field.attr("name"); // Get the name attribute of the field
            var fieldValue = field.val(); // Get the value of the field
            var validateUrl = field.closest("form").data("validate-url"); // Get the validation URL from the nearest form

            field.closest("div").find(".invalid-feedback").remove(); // Remove any existing validation feedback

            var requestData = {};
            requestData[fieldName] = fieldValue; // Prepare data object with field name and value

            $.ajax({
                url: validateUrl,
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ), // Include CSRF token from meta tag in headers
                },
                data: requestData,
                success: function (response) {},
                error: function (xhr, status, error) {
                    // Handle error response
                    if (xhr.status === 422) {
                        // If response status is 'Unprocessable Entity' (validation error)
                        var errors = xhr.responseJSON.errors; // Get the validation errors from the response

                        if (errors.hasOwnProperty(fieldName)) {
                            // Check if there are errors for the specific field
                            var errorMessage = errors[fieldName][0]; // Get the first error message for the field

                            // Add 'is-invalid' class to the field and display the error message
                            field
                                .addClass("is-invalid")
                                .after(
                                    '<div class="invalid-feedback">' +
                                        errorMessage +
                                        "</div>"
                                );
                        }
                    } else {
                        console.error("Request error:", error);
                    }
                },
            });
        });

    // --------------------------- Menus --------------------------------- //
    let menuCount = 0;

    function addMenu1() {
        menuCount++;
        let menu1Html = `
        <div class="menu1-item">
            <h3>Menu ${menuCount}</h3>
            </div>
    `;

        $("#menus1-container").append(menu1Html);

        // Adiciona os campos de input para Menu1 dentro do menu1Html
        addMenuFields(menu1Html, menuCount, 1, null, null); // 1 indica que é um Menu1

        // Adiciona um botão para adicionar submenus (Menu2)
        let addMenu2Button = `
        <button type="button" class="btn btn-secondary add-menu2" data-menu1-id="${menuCount}">Adicionar Submenu</button>
    `;
        $(menu1Html).append(addMenu2Button);
    }

    function addMenu2(menu1Id) {
        menuCount++;
        let menu2Index = menu2Count[menu1Id] + 1; // Índice do novo Menu2
        menu2Count[menu1Id] = menu2Index; // Atualiza o contador de Menu2 para este Menu1

        let menu2Html = `
            <div class="menu2-item">
            <h4>SubMenu ${menu2Index}</h4>
        </div>
        `;

        $(`#menus2-container-${menu1Id}`).append(menu2Html);

        // Adiciona o botão de excluir Menu2
        let deleteMenu2Button = `
            <button type="button" class="btn btn-danger delete-menu2" data-menu1-id="${menu1Id}" data-menu2-id="${menu2Index}">Excluir Submenu</button>
        `;
        $(menu2Html).append(deleteMenu2Button, addMenu3Button);
    }

    function addMenu3(menu1Id, menu2Id) {
        menuCount++;
        let menu3Index = menu3Count[menu1Id + "_" + menu2Id] + 1; // Índice do novo Menu3
        menu3Count[menu1Id + "_" + menu2Id] = menu3Index; // Atualiza o contador de Menu3 para este Menu2

        let menu3Html = `
            <div class="menu3-item">
                <h4>SubMenu ${menu3Index}</h4>
            </div>
        `;

        $(`#menus3-container-${menu1Id}-${menu2Id}`).append(menu3Html);

        // Adiciona o botão de excluir Menu3
        let deleteMenu3Button = `
            <button type="button" class="btn btn-danger delete-menu3" data-menu1-id="${menu1Id}" data-menu2-id="${menu2Id}" data-menu3-id="${menu3Index}">Excluir Submenu</button>
        `;
        $(menu3Html).append(deleteMenu3Button);
    }

    function addMenuFields(
        htmlContainer,
        menuIndex,
        menuLevel,
        menu1Id,
        menu2Id
    ) {
        let nameInput = createInput(
            `menus1_${menuIndex}_name`,
            "text",
            "Nome",
            `menus1[${menuIndex}][name]`
        );
        let urlInput = createInput(
            `menus1_${menuIndex}_url`,
            "text",
            "URL",
            `menus1[${menuIndex}][url]`
        );
        let iconInput = createInput(
            `menus1_${menuIndex}_icon`,
            "text",
            "Ícone",
            `menus1[${menuIndex}][icon]`
        );
        let positionInput = createInput(
            `menus1_${menuIndex}_position`,
            "number",
            "Posição",
            `menus1[${menuIndex}][position]`
        );

        $(htmlContainer).append(nameInput, urlInput, iconInput, positionInput);

        // Adiciona campos específicos para Menu2 e Menu3, se necessário
        if (menuLevel === 2) {
            let iframeInput = createInput(
                `menus1_${menu1Id}_menus2_${menuIndex}_iframe`,
                "number",
                "Iframe",
                `menus1[<span class="math-inline">\{menu1Id\}\]\[menus2\]\[</span>{menuIndex}][iframe]`
            );
            $(htmlContainer).append(iframeInput);
        } else if (menuLevel === 3) {
            let dashboardInput = createInput(
                `menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menuIndex}_dashboard`,
                "number",
                "Dashboard",
                `menus1[<span class="math-inline">\{menu1Id\}\]\[menus2\]\[</span>{menu2Id}][menus3][${menuIndex}][dashboard]`
            );
            let methodInput = createInput(
                `menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menuIndex}_method`,
                "number",
                "Method",
                `menus1[<span class="math-inline">\{menu1Id\}\]\[menus2\]\[</span>{menu2Id}][menus3][${menuIndex}][method]`
            );
            $(htmlContainer).append(dashboardInput, methodInput);
        }
    }

    // Função auxiliar para criar um elemento input
    function createInput(id, type, label, name) {
        return `
        <div class="form-group">
            <label for="<span class="math-inline">\{id\}"\></span>{label}:</label>
            <input type="<span class="math-inline">\{type\}" class\="form\-control" id\="</span>{id}" name="${name}">
        </div>
    `;
    }

    // Função para carregar os Menus1 existentes na edição
    function loadExistingMenus1(menus1Data) {
        menus1Data.forEach((menu1, menu1Index) => {
            addMenu1(); // Adiciona um novo Menu1 na página
            $(`#menus1_${menu1Index + 1}_name`).val(menu1.name);
            $(`#menus1_${menu1Index + 1}_url`).val(menu1.url);
            $(`#menus1_${menu1Index + 1}_icon`).val(menu1.icon);
            $(`#menus1_${menu1Index + 1}_position`).val(menu1.position);

            if (menu1.menus2 && menu1.menus2.length > 0) {
                loadExistingMenus2(menu1Index + 1, menu1.menus2);
            }
        });
    }

    // Função para carregar os Menus2 existentes na edição
    function loadExistingMenus2(menu1Id, menus2Data) {
        menus2Data.forEach((menu2, menu2Index) => {
            addMenu2(menu1Id);
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_name`).val(
                menu2.name
            );
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_url`).val(menu2.url);
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_icon`).val(
                menu2.icon
            );
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_position`).val(
                menu2.position
            );
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_iframe`).val(
                menu2.iframe
            );

            if (menu2.menus3 && menu2.menus3.length > 0) {
                loadExistingMenus3(menu1Id, menu2Index + 1, menu2.menus3);
            }
        });
    }

    // Função para carregar os Menus3 existentes na edição
    function loadExistingMenus3(menu1Id, menu2Id, menus3Data) {
        menus3Data.forEach((menu3, menu3Index) => {
            addMenu3(menu1Id, menu2Id);
            $(
                `#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${
                    menu3Index + 1
                }_name`
            ).val(menu3.name);
            $(
                `#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${
                    menu3Index + 1
                }_url`
            ).val(menu3.url);
            $(
                `#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${
                    menu3Index + 1
                }_icon`
            ).val(menu3.icon);
            $(
                `#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${
                    menu3Index + 1
                }_position`
            ).val(menu3.position);
            $(
                `#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${
                    menu3Index + 1
                }_dashboard`
            ).val(menu3.dashboard);
            $(
                `#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${
                    menu3Index + 1
                }_method`
            ).val(menu3.method);
        });
    }

    $("#add-menu1").on("click", addMenu1);

    // Excluir Menu2
    $(document).on("click", ".delete-menu2", function () {
        let menu1Id = $(this).data("menu1-id");
        let menu2Index = $(this).data("menu2-id");
        $(this).closest(".menu2-item").remove();
        delete menu2Count[menu1Id];
    });

    // Excluir Menu3
    $(document).on("click", ".delete-menu3", function () {
        let menu1Id = $(this).data("menu1-id");
        let menu2Id = $(this).data("menu2-id");
        $(this).closest(".menu3-item").remove();
        delete menu3Count[menu1Id + "_" + menu2Id];
    });

    if (window.location.pathname.includes("/config/menus/")) {
        let menu1Id = window.location.pathname.split("/").pop();
        $.ajax({
            url: `/menus/json/${menu1Id}`,
            method: "GET",
            success: function (menusData) {
                loadExistingMenus1(menusData);
            },
        });
    }
    // --------------------------- Menus --------------------------------- //

    // --------------------------- Faces --------------------------------- //
    function fetchFaces() {
        fetch("/corsight/faces-data")
            .then((response) => response.json())
            .then((faces) => {
                const facesList = document.getElementById("faces-list");
                facesList.innerHTML = "";
                faces.forEach((face) => {
                    const listItem = document.createElement("li");
                    listItem.className = "listItem";
                    listItem.style =
                        "opacity: 1; transform: none; margin: 10px;";
                    const div = document.createElement("div");
                    const img = document.createElement("img");
                    img.src = `/storage/${face.face_crop_img}`;
                    img.alt = "Face Image";
                    img.style =
                        "width: 160px; height: 240px; object-fit: cover;";
                    const p = document.createElement("p");
                    p.textContent = face.poi_display_name;
                    div.appendChild(img);
                    div.appendChild(p);
                    listItem.appendChild(div);
                    facesList.appendChild(listItem);
                });
            });
    }

    setInterval(fetchFaces(), 5000);
    // --------------------------- Faces --------------------------------- //
});

// global-datatables.js
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        paginate: {
            // "first": "Primeiro",
            // "last": "Último",
            next: "Próximo",
            previous: "Anterior",
        },
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "Nenhum registro encontrado",
        emptyTable: "Nenhum dado disponível na tabela",
        info: "Mostrando _START_ até _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 até 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros no total)",
    },
    pagingType: "simple_numbers",
});
