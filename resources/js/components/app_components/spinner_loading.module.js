const spinner_show = () => {
    $("#LoadingOverlayApi").addClass("loading-overlay-showing");
};

const spinner_hide = () => {
    $("#LoadingOverlayApi").removeClass("loading-overlay-showing");
};

export { spinner_show, spinner_hide };
