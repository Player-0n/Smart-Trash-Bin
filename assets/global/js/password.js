function togglePasswords(inputId) {
    const passwordInputs = document.querySelectorAll(`#${inputId}`);

    passwordInputs.forEach((field) => {
        field.type = field.type === "password" ? "text" : "password";
    });
}