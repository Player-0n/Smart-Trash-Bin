document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("toggle-passwords").addEventListener("click", function() {
        const currentPassword = document.getElementById("curr-password");
        const newPassword = document.getElementById("new-pword");
        const confirmPassword = document.getElementById("conf-new-pword");

        if(currentPassword.type === "password") {
            currentPassword.type = "text";
            newPassword.type = "text";
            confirmPassword.type = "text";
        }

        else {
            currentPassword.type = "password";
            newPassword.type = "password";
            confirmPassword.type = "password";
        }
    });
    
    document.getElementById('upload-pic').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const picture = document.querySelectorAll('#profile-preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                picture.forEach((img) => {
                    img.src = e.target.result;
                })
            };
            reader.readAsDataURL(file);
        }
    });
})