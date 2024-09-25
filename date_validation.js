function validateDate() {
    let date = document.getElementById("date").value;
    let today = new Date().toISOString().split('T')[0];

    if (date < today) {
        alert("The date cannot be in the past.");
        return false;
    }

    return true;
}
