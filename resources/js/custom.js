$(document).ready(function () {
    // Variables
    const cpfElement = $("#cpf");
    let menuCount = 0;
    const menu2Count = {};
    const menu3Count = {};

    // Attach input event listeners
    cpfElement.text(formatCPF(cpfElement.text()));
    $("#cep").on("input", maskCEPInput);
    $("#cpf").on("input", formatCPFInput);
    $("#cep").blur(handleCEPBlur);
    $(".real-time-validation").find(".form-control").blur(handleRealTimeValidation);
    $("#add-menu1").on("click", addMenu1);
    $(document).on("click", ".add-menu2", addMenu2);
    $(document).on("click", ".delete-menu2", deleteMenu2);
    $(document).on("click", ".delete-menu3", deleteMenu3);

    if (window.location.pathname.includes("/config/menus/")) {
        const menu1Id = window.location.pathname.split("/").pop();
        $.ajax({
            url: `/menus/json/${menu1Id}`,
            method: "GET",
            success: function (menusData) {
                loadExistingMenus1(menusData);
            },
        });
    }
    
    // Function to format CPF dynamically
    function formatCPFInput(event) {
        let input = event.target;
        let value = input.value.replace(/\D/g, ""); // Remove non-digit characters
        value = value.slice(0, 11); // Limit to 11 digits

        if (value.length > 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        } else if (value.length > 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})/, "$1.$2.$3");
        } else if (value.length > 3) {
            value = value.replace(/(\d{3})(\d{3})/, "$1.$2");
        }
        input.value = value;
    }

    // Function to format CPF
    function formatCPF(cpf) {
        cpf = cpf.replace(/\D/g, ""); // Remove non-digit characters from CPF
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"); // Format CPF with dots and dash
    }

    // Function to handle CEP input mask
    function maskCEPInput(event) {
        let input = event.target;
        let value = input.value.replace(/\D/g, ""); // Remove non-digit characters
        value = value.slice(0, 8); // Limit to 8 digits

        if (value.length > 5) {
            value = value.slice(0, 5) + "-" + value.slice(5);
        }
        input.value = value;
    }

    // Function to handle CEP blur event for fetching address data
    function handleCEPBlur() {
        const cep = $(this).val().replace(/\D/g, ""); // Remove non-digit characters
        if (cep.length === 8) {
            fetchAddressData(cep);
        }
    }

    // Function to fetch address data based on CEP
    function fetchAddressData(cep) {
        $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function (data) {
            if (!("erro" in data)) {
                $("#address").val(data.logradouro);
                $("#district").val(data.bairro);
                $("#city").val(data.localidade);
                $("#state").val(data.uf);
            } else {
                clearAddressFields();
                showNotification("CEP not found.", "warning");
            }
        });
    }

    // Function to clear address input fields
    function clearAddressFields() {
        $("#address").val("");
        $("#district").val("");
        $("#city").val("");
        $("#state").val("");
    }

    // Function to show notification
    function showNotification(message, type) {
        noty("", message, type);
    }

    // Function to handle real-time validation
    function handleRealTimeValidation() {
        const field = $(this);
        const fieldName = field.attr("name");
        const fieldValue = field.val();
        const validateUrl = field.closest("form").data("validate-url");

        field.closest("div").find(".invalid-feedback").remove();

        const requestData = {};
        requestData[fieldName] = fieldValue;

        $.ajax({
            url: validateUrl,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: requestData,
            success: function (response) { },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.hasOwnProperty(fieldName)) {
                        const errorMessage = errors[fieldName][0];
                        field.addClass("is-invalid").after(
                            `<div class="invalid-feedback">${errorMessage}</div>`
                        );
                    }
                } else {
                    console.error("Request error:", xhr);
                }
            },
        });
    }

    // Function to add Menu1
    function addMenu1() {
        menuCount++;
        const menu1Html = `
            <div class="menu1-item">
                <h3>Menu ${menuCount}</h3>
                <button type="button" class="btn btn-secondary add-menu2" data-menu1-id="${menuCount}">Add Submenu</button>
                <div id="menus2-container-${menuCount}"></div>
            </div>
        `;

        $("#menus1-container").append(menu1Html);
        addMenuFields(`#menus2-container-${menuCount}`, menuCount, 1);
    }

    // Function to add Menu2
    function addMenu2(event) {
        const menu1Id = $(event.target).data("menu1-id");
        menu2Count[menu1Id] = menu2Count[menu1Id] || 0;
        const menu2Index = ++menu2Count[menu1Id];

        const menu2Html = `
            <div class="menu2-item">
                <h4>SubMenu ${menu2Index}</h4>
                <button type="button" class="btn btn-danger delete-menu2" data-menu1-id="${menu1Id}" data-menu2-id="${menu2Index}">Delete Submenu</button>
                <div id="menus3-container-${menu1Id}-${menu2Index}"></div>
            </div>
        `;

        $(`#menus2-container-${menu1Id}`).append(menu2Html);
        addMenuFields(`#menus3-container-${menu1Id}-${menu2Index}`, menu2Index, 2, menu1Id);
    }

    // Function to add Menu3
    function addMenu3(menu1Id, menu2Id) {
        menu3Count[`${menu1Id}_${menu2Id}`] = menu3Count[`${menu1Id}_${menu2Id}`] || 0;
        const menu3Index = ++menu3Count[`${menu1Id}_${menu2Id}`];

        const menu3Html = `
            <div class="menu3-item">
                <h4>SubMenu ${menu3Index}</h4>
                <button type="button" class="btn btn-danger delete-menu3" data-menu1-id="${menu1Id}" data-menu2-id="${menu2Id}" data-menu3-id="${menu3Index}">Delete Submenu</button>
            </div>
        `;

        $(`#menus3-container-${menu1Id}-${menu2Id}`).append(menu3Html);
        addMenuFields(`#menus3-container-${menu1Id}-${menu2Id}`, menu3Index, 3, menu1Id, menu2Id);
    }

    // Function to add menu fields
    function addMenuFields(container, index, level, menu1Id, menu2Id) {
        const nameInput = createInput(`menus1_${index}_name`, "text", "Name", `menus1[${index}][name]`);
        const urlInput = createInput(`menus1_${index}_url`, "text", "URL", `menus1[${index}][url]`);
        const iconInput = createInput(`menus1_${index}_icon`, "text", "Icon", `menus1[${index}][icon]`);
        const positionInput = createInput(`menus1_${index}_position`, "number", "Position", `menus1[${index}][position]`);

        $(container).append(nameInput, urlInput, iconInput, positionInput);

        if (level === 2) {
            const iframeInput = createInput(`menus1_${menu1Id}_menus2_${index}_iframe`, "number", "Iframe", `menus1[${menu1Id}][menus2][${index}][iframe]`);
            $(container).append(iframeInput);
        } else if (level === 3) {
            const dashboardInput = createInput(`menus1_${menu1Id}_menus2_${menu2Id}_menus3_${index}_dashboard`, "number", "Dashboard", `menus1[${menu1Id}][menus2][${menu2Id}][menus3][${index}][dashboard]`);
            const methodInput = createInput(`menus1_${menu1Id}_menus2_${menu2Id}_menus3_${index}_method`, "number", "Method", `menus1[${menu1Id}][menus2][${menu2Id}][menus3][${index}][method]`);
            $(container).append(dashboardInput, methodInput);
        }
    }

    // Function to create an input element
    function createInput(id, type, label, name) {
        return `
            <div class="form-group">
                <label for="${id}">${label}:</label>
                <input type="${type}" class="form-control" id="${id}" name="${name}">
            </div>
        `;
    }

    // Function to load existing Menu1 data
    function loadExistingMenus1(menus1Data) {
        menus1Data.forEach((menu1, menu1Index) => {
            addMenu1();
            $(`#menus1_${menu1Index + 1}_name`).val(menu1.name);
            $(`#menus1_${menu1Index + 1}_url`).val(menu1.url);
            $(`#menus1_${menu1Index + 1}_icon`).val(menu1.icon);
            $(`#menus1_${menu1Index + 1}_position`).val(menu1.position);

            if (menu1.menus2) {
                loadExistingMenus2(menu1Index + 1, menu1.menus2);
            }
        });
    }

    // Function to load existing Menu2 data
    function loadExistingMenus2(menu1Id, menus2Data) {
        menus2Data.forEach((menu2, menu2Index) => {
            addMenu2({ target: { dataset: { menu1Id } } });
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_name`).val(menu2.name);
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_url`).val(menu2.url);
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_icon`).val(menu2.icon);
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_position`).val(menu2.position);
            $(`#menus1_${menu1Id}_menus2_${menu2Index + 1}_iframe`).val(menu2.iframe);

            if (menu2.menus3) {
                loadExistingMenus3(menu1Id, menu2Index + 1, menu2.menus3);
            }
        });
    }

    // Function to load existing Menu3 data
    function loadExistingMenus3(menu1Id, menu2Id, menus3Data) {
        menus3Data.forEach((menu3, menu3Index) => {
            addMenu3(menu1Id, menu2Id);
            $(`#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menu3Index + 1}_name`).val(menu3.name);
            $(`#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menu3Index + 1}_url`).val(menu3.url);
            $(`#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menu3Index + 1}_icon`).val(menu3.icon);
            $(`#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menu3Index + 1}_position`).val(menu3.position);
            $(`#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menu3Index + 1}_dashboard`).val(menu3.dashboard);
            $(`#menus1_${menu1Id}_menus2_${menu2Id}_menus3_${menu3Index + 1}_method`).val(menu3.method);
        });
    }

    // Function to delete Menu2
    function deleteMenu2() {
        const menu1Id = $(this).data("menu1-id");
        const menu2Id = $(this).data("menu2-id");
        $(this).closest(".menu2-item").remove();
        delete menu2Count[menu1Id];
    }

    // Function to delete Menu3
    function deleteMenu3() {
        const menu1Id = $(this).data("menu1-id");
        const menu2Id = $(this).data("menu2-id");
        $(this).closest(".menu3-item").remove();
        delete menu3Count[`${menu1Id}_${menu2Id}`];
    }
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
