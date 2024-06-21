const noty = (title, text, type) => {
    new PNotify({
        title: title,
        text: text,
        type: type,
        addclass: 'stack-bottomright ui-pnotify-no-icon',
        icon: false,
        stack: {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15}
    });
};

export default noty;
