$("#register").submit(function (e) {
   

    if (parseInt($('#age').val()) < 18) {
        alert("Age must be above 18.");
    }


    if ($('#postcode').val() != '') {
        if (validatePostcode($('#postcode').val()) == false) {
            alert("invalid postcode. It must be in XX-XXX");
        }
    }


    if (!emailValidator($('#email').val())) {
        alert("Invalid Email address.");
    }

    e.preventDefault(); // avoid to execute the actual submit of the form.

    // Creating form data to send in API.
    var form = {
        "user_name": $('#username').val(),
        "user_id": $('#user_id').val(),
        "user_email": $('#email').val(),
        "user_phone": $('#phonenumber').val(),
        "age": $('#age').val(),
        "city": $('#city').val(),
        "post_code": $('#postcode').val(),
        "address": $('#address').val()
    }
    
    // Calling data update API via AJAX.
    var actionUrl = API_HOST + "endpoints/usermaster-update.php";
    $.ajax({
        type: "POST",
        url: actionUrl,
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        data: JSON.stringify(form), // serializes the form's elements.
        success: function (data) {
            if (data.status == "Success") {
                alert("Update Successful"); // show response from the php script.
                location.reload();
                window.location.href = "dashboard.html";
            }
            else {
                alert(data.msg);

            }
            
        }
    });

});



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

function param(name) {
    return (location.search.split(name + '=')[1] || '').split('&')[0];
}

$(document).ready(function () {

    
    var actionUrl = API_HOST + "endpoints/getuserdatabyid.php";
    var data_ = [];

    var form = {
        "user_id": param("id")

    }
    // Calling API to fetch the data for a particular user id.
    $.ajax({
        type: "POST",
        url: actionUrl,
        contentType: 'application/json; charset=utf-8',
        data: JSON.stringify(form),
        dataType: 'json',

        success: function (data) {
            
            $('#user_id').val(data[0]["user_id"]);
            $('#username').val(data[0]["user_name"]);
            $('#email').val(data[0]["user_email"]);
            $('#phonenumber').val(data[0]["user_phone"]);
            $('#age').val(data[0]["age"]);
            $('#address').val(data[0]["address"]);
            $('#postcode').val(data[0]["post_code"]);
            $('#city').val(data[0]["city"]);
        }
    });
});


