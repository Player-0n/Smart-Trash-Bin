document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('upload-item-pic').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('item-preview');
        if (file) {
            preview.src = URL.createObjectURL(file);
        }
    });
})