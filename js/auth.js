document.querySelectorAll(".toggle-password").forEach(button => {

    button.addEventListener("click", function () {

        const input = document.getElementById(this.dataset.target);

        if (!input) return;

        const icon = this.querySelector("i");

        // Password is currently hidden
        if (input.type === "password") {

            // Show password
            input.type = "text";

            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");

        } else {

            // Hide password
            input.type = "password";

            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");

        }

    });

});