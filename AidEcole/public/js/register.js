document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitButton = document.querySelector("button[type='submit']");
    const passwordInput = document.getElementById("registrationForm_password_first");
    const confirmPasswordInput = document.getElementById("registrationForm_password_second");
    const emailInput = document.getElementById("registrationForm_email");
    const telInput = document.getElementById("registrationForm_tel");
    const ribInput = document.getElementById("registrationForm_rib");
    const codePostalInput = document.getElementById("registrationForm_code_postal");

    function validateInput(input, regex, errorMessage) {
        input.addEventListener("input", function () {
            if (!regex.test(input.value)) {
                input.setCustomValidity(errorMessage);
            } else {
                input.setCustomValidity("");
            }
        });
    }

    function validatePassword() {
        const password = passwordInput.value;
        const minLength = /.{8,}/;
        const uppercase = /[A-Z]/;
        const specialChar = /[!@#$%^&*(),.?":{}|<>]/;

        if (!minLength.test(password) || !uppercase.test(password) || !specialChar.test(password)) {
            passwordInput.setCustomValidity("Le mot de passe doit contenir au moins 8 caractères, une majuscule et un symbole.");
        } else {
            passwordInput.setCustomValidity("");
        }
    }

    function validateConfirmPassword() {
        confirmPasswordInput.setCustomValidity(
            confirmPasswordInput.value !== passwordInput.value ? "Les mots de passe ne correspondent pas." : ""
        );
    }

    // Appliquer la validation en temps réel
    validateInput(emailInput, /^[^\s@]+@[^\s@]+\.[^\s@]+$/, "Veuillez entrer une adresse e-mail valide.");
    validateInput(telInput, /^\d{8,15}$/, "Le numéro de téléphone doit contenir entre 8 et 15 chiffres.");
    if (ribInput) validateInput(ribInput, /^\d{16,24}$/, "Le RIB doit contenir entre 16 et 24 chiffres.");
    if (codePostalInput) validateInput(codePostalInput, /^\d{4,6}$/, "Le code postal doit contenir entre 4 et 6 chiffres.");

    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", validateConfirmPassword);

    // Bloquer la soumission si des erreurs existent
    form.addEventListener("submit", function (event) {
        validatePassword();
        validateConfirmPassword();

        if (!form.checkValidity()) {
            event.preventDefault(); // Empêche l'envoi si une erreur est détectée
            form.reportValidity(); // Affiche immédiatement les erreurs
        }
    });

    // Vérification immédiate lorsque l'utilisateur clique sur le bouton
    submitButton.addEventListener("click", function () {
        validatePassword();
        validateConfirmPassword();
        form.reportValidity();
    });
});
