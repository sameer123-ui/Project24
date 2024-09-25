function validateEmployeeID() {
    let employeeID = document.getElementById("employee_id").value;
    let pattern = /^[E][0-9]{4}$/; // Example: E1234

    if (!pattern.test(employeeID)) {
        alert("Employee ID must be in the format 'E' followed by 4 digits (e.g., E1234).");
        return false;
    }

    return true;
}
