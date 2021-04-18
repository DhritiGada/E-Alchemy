const registerForm = document.getElementById('register-form');
const messageBox = document.getElementById('messages');

const fnameInput = registerForm.querySelector('#fname-input');
const lnameInput = registerForm.querySelector('#lname-input');
const unameInput = registerForm.querySelector('#uname-input');
const emailInput = registerForm.querySelector('#email-input');
const pwd1Input = registerForm.querySelector('#pwd1-input');
const pwd2Input = registerForm.querySelector('#pwd2-input');

fnameInput.addEventListener("keyup", checkFirstName);
lnameInput.addEventListener("keyup", checkLastName);
unameInput.addEventListener("keyup", checkUsername);
emailInput.addEventListener("keyup", checkEmail);
pwd1Input.addEventListener("keyup", checkPassword1);
pwd2Input.addEventListener("keyup", checkPassword2);

registerForm.addEventListener("submit", postDataToServer);

let hasErrors = true;

setInterval(() => {
    enableOrDisableSubmitButton()
}, 1000);


function postDataToServer(e) {
    let xml = new XMLHttpRequest();
    xml.open("POST", "includes/handlers/register-handler.php", true);

    xml.onload = () => {
        // console.log(xml.responseText);

        let response = JSON.parse(xml.responseText);
        // console.log(response);

        if (response === "success") {
            startVisualRedirectTimer();
        } else {
            messageBox.innerHTML = '<div class="ui red message">' + response + '</div>';

            // Reset recaptcha
            grecaptcha.reset();
        }
    }

    let data = new FormData(registerForm);
    data.append("g-recaptcha-response", grecaptcha.getResponse());
    xml.send(data);

    e.preventDefault();
}

function checkFirstName(e) {
    validateInputField("fname", e.target.value, fnameInput.parentElement)
}

function checkLastName(e) {
    validateInputField("lname", e.target.value, lnameInput.parentElement)
}

function checkUsername(e) {
    validateInputField("uname", e.target.value, unameInput.parentElement)
}

function checkEmail(e) {
    validateInputField("email", e.target.value, emailInput.parentElement)
}

function checkPassword1(e) {
    validateInputField("pwd1", e.target.value, pwd1Input.parentElement)
    pwd2Input.value = "";
    hideCheckIcon(pwd2Input.parentElement)
}

function checkPassword2(e) {
    let parentOfInputBox = e.target.parentElement;
    let pwd2 = e.target.value;

    showLoadingIcon(parentOfInputBox);

    setTimeout(() => {
        if (pwd2 !== pwd1Input.value) {
            parentOfInputBox.parentElement.classList.add("error");
            messageBox.innerHTML = "";
            messageBox.innerHTML = "<div class='ui red message'>Your passwords don't match.</div>";
            hideLoadingIcon(parentOfInputBox);
            hideCheckIcon(parentOfInputBox);
        } else {
            messageBox.innerHTML = "";
            parentOfInputBox.parentElement.classList.remove("error");
            hideLoadingIcon(parentOfInputBox);
            showCheckIcon(parentOfInputBox);
        }
    }, 1500);
}

function enableOrDisableSubmitButton() {
    let registerBtn = document.getElementById('registerBtn');

    if (grecaptcha.getResponse() !== "" && hasErrors == false) {
        registerBtn.classList.remove("disabled")
    } else {
        if (Array.from(registerBtn.classList).indexOf("disabled") == -1) {
            registerBtn.classList.add("disabled")
        }
    }
}

function validateInputField(inputBoxId, inputValue, parentOfInputBox) {

    if (inputValue.length > 0) {
        // Errors in type
        showLoadingIcon(parentOfInputBox);

        setTimeout(() => {
            let http = new XMLHttpRequest();
            http.open("POST", "includes/handlers/ajax/register-form-checks.php", true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            http.onload = () => {
                // console.log(http.responseText);
                let errors = JSON.parse(http.responseText);

                if (errors.length > 0) {
                    let outputErrors = ""

                    errors.forEach(error => {
                        outputErrors += '<div class="ui red message">' + error + '</div>';
                    });

                    parentOfInputBox.parentElement.classList.add("error");

                    messageBox.innerHTML = "";
                    messageBox.innerHTML = outputErrors;

                    hideLoadingIcon(parentOfInputBox);
                    hideCheckIcon(parentOfInputBox);

                    hasErrors = true;
                } else {
                    parentOfInputBox.parentElement.classList.remove("error");
                    messageBox.innerHTML = "";

                    hideLoadingIcon(parentOfInputBox);
                    showCheckIcon(parentOfInputBox);

                    hasErrors = false;
                }
            }
            let data = `${inputBoxId}=` + inputValue;
            http.send(data);

        }, 1500);

    } else {
        parentOfInputBox.parentElement.classList.remove("error");

        hideLoadingIcon(parentOfInputBox);
        hideCheckIcon(parentOfInputBox);

        hasErrors = true;
    }
    messageBox.innerHTML = "";
}

function startVisualRedirectTimer() {
    let seconds = -5;

    setInterval(() => {
        messageBox.innerHTML = '<div class="ui blue message">' + "Your account was created successfully." + '</br>' + "Redirecting in " + Math.abs(seconds) + " seconds." + '</div>';
        seconds++;
        if (seconds == 0) {
            // Go to dashboard
            window.location = "dashboard.php";
        }
    }, 1000);

}

function showLoadingIcon(parentOfInputBox) {
    parentOfInputBox.querySelector("i.check.icon").style.display = "none";
    parentOfInputBox.querySelector("i.notched.circle.loading.icon").style.display = "block";
}
function hideLoadingIcon(parentOfInputBox) {
    parentOfInputBox.querySelector("i.notched.circle.loading.icon").style.display = "none";
}
function showCheckIcon(parentOfInputBox) {
    parentOfInputBox.querySelector("i.check.icon").style.display = "block";
}
function hideCheckIcon(parentOfInputBox) {
    parentOfInputBox.querySelector("i.check.icon").style.display = "none";
}
