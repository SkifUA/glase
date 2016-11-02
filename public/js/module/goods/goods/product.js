
var TEXT_MESSAGE_BET_ERROR_DB = 'Error DB: Your Bet is not accepted';
var TEXT_MESSAGE_CHECK_ERROR_DB = 'Error DB: Log in please';

$(document).ready(function() {
    $('#buttonCheckPriceNow').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/goods/check-price",
            dataType: "json",
            data: "id=" + id,
            cache: false,
            success: function (data) {
                if (data) {
                    processingData(data);
                }
            },
            error: function (xhr, str) {
                var errorData = [];
                errorData.db = TEXT_MESSAGE_CHECK_ERROR_DB;
                showErrorMessage(errorData);
            }
        });
    });

    $('#buttonMakeBetLot').on('click', function() {
        var id = $(this).data('id');
        var price = $(this).data('price');
        $.ajax({
            type: "POST",
            url: "/goods/make-bet",
            dataType: "json",
            data: "id=" + id + "&price=" + price,
            cache: false,
            success: function (data) {
                if (data) {
                    processingData(data);
                }
            },
            error: function (xhr, str) {
                var errorData = [];
                errorData.db = TEXT_MESSAGE_BET_ERROR_DB;
                showErrorMessage(errorData);
            }
        });
    });


    function updateDataProduct(data) {

        var buyer = '';
        var dynamic = 'inactive';
        if (data.price) {
            $('#productPriceNow').val(' $ ' + data.price);
            $('#buttonMakeBetLot').data('price', String(data.price * 100));
        }

        if (data.buyer) {
            buyer ='It is Your bet';
        }
        if (data.dynamic) {
            dynamic ='active';
        }

        $('#buyerProductInfo').text(buyer);
        $('#spanProductDynamic').text(dynamic);
    }

    function processingData(data) {
        for( var row in data) {
            if ('info' == row) {
                showInfoMessage(data[row]);
            }
            if ('error' == row) {
                showErrorMessage(data[row]);
            }
            if ('data' == row) {
                updateDataProduct(data[row]);
            }
        }
    }
});



