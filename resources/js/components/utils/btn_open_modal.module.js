$(".modal-basic").magnificPopup({
    type: "inline",
    preloader: false,
    modal: true,
});

$(".modal-with-zoom-anim").magnificPopup({
    type: "inline",

    fixedContentPos: false,
    fixedBgPos: true,

    overflowY: "auto",

    closeBtnInside: true,
    preloader: false,

    midClick: true,
    removalDelay: 300,
    mainClass: "my-mfp-zoom-in",
    modal: true,
});
