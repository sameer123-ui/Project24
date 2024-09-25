function validateNumber() {
    let number = document.getElementById("number").value;

    if (isNaN(number)) {
        alert("Please enter a valid number.");
        return false;
    }

    if (number <= 0) {
        alert("Number must be greater than zero.");
        return false;
    }

    return true;
}
