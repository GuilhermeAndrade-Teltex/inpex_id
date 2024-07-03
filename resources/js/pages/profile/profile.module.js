$(document).on('change', '#profilePicture', function (e) {
    previewImage(e);
});

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) {
        console.error('Nenhum arquivo foi selecionado');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('profilePicturePreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}