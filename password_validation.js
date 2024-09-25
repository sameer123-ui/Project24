function validatePassword() {
    let password = document.getElementById("password").value;

    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    }

    if (!/[A-Z]/.test(password)) {
        alert("Password must contain at least one uppercase letter.");
        return false;
    }

    if (!/[a-z]/.test(password)) {
        alert("Password must contain at least one lowercase letter.");
        return false;
    }

    if (!/[0-9]/.test(password)) {
        alert("Password must contain at least one number.");
        return false;
    }

    if (!/[!@#\$%\^&\*]/.test(password)) {
        alert("Password must contain at least one special character.");
        return false;
    }

    return true;
}
