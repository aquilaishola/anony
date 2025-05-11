function validatePassword() {
    const password = document.getElementById("password").value;
    const lengthCheck = document.getElementById("lengthCheck");
    const specialCharCheck = document.getElementById("specialCharCheck");
    const numberCheck = document.getElementById("numberCheck");
    const submitButton = document.getElementById("signupButton");

    const lengthValid = password.length >= 6;
    const specialCharValid = /[\W]/.test(password);
    const numberValid = /[0-9]/.test(password);

    lengthCheck.checked = lengthValid;
    specialCharCheck.checked = specialCharValid;
    numberCheck.checked = numberValid;

    submitButton.disabled = !(lengthValid && specialCharValid && numberValid);
}