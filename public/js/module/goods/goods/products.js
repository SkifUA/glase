var PAGINATOR_NEXT = '>>';
var PAGINATOR_PREVIVIOUS = '<<';
var TEXT_MESSAGE_GOODS_PRODUCTS_ERROR_DB = 'Error DB: Log in please';


$(document).ready(function() {

    $('#inputSearchProduct').bind('textchange', function (event, previousText) {

        var text = $(this).val();
        var activeId = $('.order-arrow.order-active').attr('id');
        var dataOrder = getOrderSortingById(activeId);
        var page = $('ul.pagination > li.active > a').html();
        var sendData = 'text=' + text + '&page=' + page +'&order=' + dataOrder.order + '&column=' + dataOrder.column;

        selectDataForProducts(sendData);
    });

    documentReady();
});

function reversOrder(order) {
    if (order == 'ASC') {
        return 'DESC';
    }
    return 'ASC';
}

function getOrderSortingById(id) {
    var result = [];
    var order,
        column;

    if (id.indexOf('Asc') > 0) {
        order = 'ASC';
        column = id.substring(0, id.indexOf('Asc'));
    } else {
        order = 'DESC';
        column = id.substring(0, id.indexOf('Desc'));
    }
    result.order = order;
    result.column = column;
    return result;
}

function selectDataForProducts(sendData) {
    $.ajax({
        type: "POST",
        url: "/goods/select",
        data: sendData,
        dataType: "html",
        cache: false,
        success: function (data) {
            $('#listProducts').html(data);
            documentReady();
        },
        error: function (xhr, str) {
            var errorData = [];
            errorData.db = TEXT_MESSAGE_GOODS_PRODUCTS_ERROR_DB;
            showErrorMessage(errorData);
        }
    });
}

function getActivePage() {
    var page = $('ul.pagination > li.active > a').text();
    return page;
}

function getMaxNumberPage() {
    var maxNumber = 1;
    $('ul.pagination > li > a').each(function(){
        var number = parseInt($(this).text());
        if (maxNumber < number) {
            maxNumber = number;
        }
    });
    return maxNumber;
}

function getPage(text) {
    if (PAGINATOR_NEXT != text.trim() && PAGINATOR_PREVIVIOUS != text.trim()) {
        return parseInt(text);
    }

    var activePage = parseInt(getActivePage());
    if (PAGINATOR_PREVIVIOUS == text.trim()) {
        if (1 == activePage) {
            return 1;
        }
        return activePage - 1;
    }

    if (PAGINATOR_NEXT == text.trim()) {
        var maxNumber = getMaxNumberPage();
        if (maxNumber == activePage) {
            return maxNumber;
        }
        return activePage + 1;
    }

}

function documentReady() {

    $('.sort-button').on('click', function() {

        var column = $(this).data('column');
        var order = reversOrder($(this).data('order'));
        var page =  getActivePage();
        var text = $('#inputSearchProduct').val();
        var sendData = 'order=' + order + '&column=' + column + '&page=' + page +' &text=' + text;

        selectDataForProducts(sendData);
    });

    $('ul.pagination > li > a').on('click', function() {

        var page = getPage($(this).text());
        var text = $('#inputSearchProduct').val();
        var activeId = $('.order-arrow.order-active').attr('id');
        var dataOrder = getOrderSortingById(activeId);
        var sendData = 'text=' + text + '&page=' + page +'&order=' + dataOrder.order + '&column=' + dataOrder.column;

        selectDataForProducts(sendData);
    });
}