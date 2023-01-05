$("#register").submit(function (e) {
   

    if (parseInt($('#age').val()) < 18) {
        alert("Age must be above 18.");
        return false;
    }


    if ($('#postcode').val() != '') {
        if (validatePostcode($('#postcode').val()) == false) {
            alert("invalid postcode. It must be in XX-XXX");
            return false;
        }
    }


    if (!emailValidator($('#email').val())) {
        alert("Invalid Email address 1.");
        return false;
    }

    e.preventDefault(); // avoid to execute the actual submit of the form.
    // Creating form data to send in API.
    var form = {
        "user_name": $('#username').val(),
        "user_password": $('#password').val(),
        "user_email": $('#email').val(),
        "user_phone": $('#phonenumber').val(),
        "age": $('#age').val(),
        "city": $('#city').val(),
        "post_code": $('#postcode').val(),
        "address": $('#address').val()
    }
    

    // Calling registration API via AJAX.
    var actionUrl = API_HOST + "endpoints/usermaster-ins.php";
    $.ajax({
        type: "POST",
        url: actionUrl,
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        data: JSON.stringify(form), // serializes the form's elements.
        success: function (data) {
            if (data.status == "Success") {
                alert("Registration Successful"); 
                location.reload();
            }
            else {
                if (data.statusCode == 1)
                    alert("Invalid Mobile Number."); 
                else if (data.statusCode == 2)
                    alert("Invalid Email Address."); 

            }

        }
    });
});


// for log in

$("#login").submit(function (e) {
    

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = {

        "user_password": $('#password_login').val(),
        "user_email": $('#email_login').val()

    }
     // Calling login API via AJAX.
    var actionUrl = API_HOST + "endpoints/login.php";
    $.ajax({
        type: "POST",
        url: actionUrl,
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        data: JSON.stringify(form), // serializes the form's elements.
        success: function (data) {
            if (data.status == "Success") {

                window.location.href = "dashboard.html";
            }
            else {
                alert("Wrong Credentials."); 
            }
            

        }
    });

});

// 
function validatePostcode(pc) {
    if (!pc.toString().includes("-")) {
        return false;
    }
    if (pc.toString().split('-')[0].length != 2 || pc.toString().split('-')[1].length != 3) {
        return false;
    }

    return true;
}

function emailValidator(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

$(document).on("input", "#phonenumber", function () {
    this.value = this.value.replace(/\D/g, '');
});


$(document).on("input", "#age", function () {
    this.value = this.value.replace(/\D/g, '');
});