$(document).ready(function () {
    var actionUrl = API_HOST + "endpoints/dashboard-data.php";
    var data_ = [];

    // Callimg dashboard data API via AJAX and binding the data into datatable.
    $.ajax({
        type: "POST",
        url: actionUrl,
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',

        success: function (data) {

            
            data_ = data.data;
            $('#example').DataTable({
                data: data_
            });

        }
    });
});


