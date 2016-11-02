
function showInfoMessage(data) {
    var print = '';
    for ( var row in data) {
        print += '<li>' + data[row] + '</li>'
    }
    $('#infoMessages').html(print);
}

function showErrorMessage(data) {
    var print = '';
    for ( var row in data) {
        print += '<li>' + data[row] + '</li>'
    }
    $('#errorMessages').html(print)
}
