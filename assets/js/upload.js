const titleInput = document.getElementById('title-input');
const uploadForm = document.getElementById('upload-form');

const messageBox = document.getElementById('messages');

titleInput.addEventListener("keyup", checkTitle);

uploadForm.addEventListener("submit", postDataToServer);


function postDataToServer(e) {
    let xml = new XMLHttpRequest();
    xml.open("POST", "includes/handlers/upload-course.php", true)
    xml.onload = () => {
        console.log(xml.responseText);
        let response = JSON.parse(xml.responseText);
        // console.log(response);
        if (response === "success") {
            messageBox.innerHTML = '<div class="ui blue message">' + "Course was uploaded successfully" + '</div>';
            uploadForm.reset();
            hideCheckIcon(titleInput.parentElement)
            document.getElementById('progressBar').style.display = "none";

            setTimeout(() => {
                window.location = "adminpanel.php";
            }, 3000);
        } else {
            messageBox.innerHTML = '<div class="ui red message">' + response + '</div>';
        }
    }

    // Upload progress on request.upload
    xml.upload.addEventListener('progress', function (e) {
        var percent_complete = (e.loaded / e.total) * 100;

        document.getElementById('progressBar').style.display = "block";

        // Percentage of upload completed
        $('#progressBar').progress({
            percent: percent_complete
        });
    });

    let data = new FormData(uploadForm);
    xml.send(data);

    e.preventDefault();
}

function checkTitle(e) {
    validateInputField("title", e.target.value, titleInput.parentElement)
}

function validateInputField(type, typeValue, field) {

    if (typeValue.length > 0) {
        // Errors in type
        showLoadingIcon(field);

        setTimeout(() => {
            let http = new XMLHttpRequest();
            http.open("POST", "includes/handlers/ajax/upload-form-checks.php", true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
            http.onload = () => {
                // console.log(http.responseText);
                let errors = JSON.parse(http.responseText);
                if (errors.length > 0) {
                    let output = ""
                    errors.forEach(error => {
                        output += '<div class="ui red message">' + error + '</div>';
                    });
                    field.parentElement.classList.add("error");
                    messageBox.innerHTML = "";
                    messageBox.innerHTML = output;
                    hideLoadingIcon(field);
                    hideCheckIcon(field);
                    errorsCheck = true;
                } else {
                    field.parentElement.classList.remove("error");
                    messageBox.innerHTML = "";
                    hideLoadingIcon(field);
                    showCheckIcon(field);
                    errorsCheck = false;
                }
            }
            let data = `${type}=` + typeValue;
            http.send(data);
        }, 1500);
    } else {
        field.parentElement.classList.remove("error");
        hideLoadingIcon(field);
        hideCheckIcon(field);
        errorsCheck = true;
    }

    messageBox.innerHTML = "";
}
function showLoadingIcon(field) {
    field.querySelector("i.check.icon").style.display = "none";
    field.querySelector("i.notched.circle.loading.icon").style.display = "block";
}
function hideLoadingIcon(field) {
    field.querySelector("i.notched.circle.loading.icon").style.display = "none";
}
function showCheckIcon(field) {
    field.querySelector("i.check.icon").style.display = "block";
}
function hideCheckIcon(field) {
    field.querySelector("i.check.icon").style.display = "none";
}