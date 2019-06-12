var selected_offer = [];
var selected_trigger = [];
var currency_format = '';
function generatePagination(totalPage, selectPage, place = '') {

    if (place) {
        $('#'+place+'_pagination').html('');
        for (let i = 1; i <= totalPage; i++) {
            if (i == selectPage) {
                $('#' + place + '_pagination').append($('<li class="page-item active">').append($('<span>', {
                    class: place.replace('_','-') + '-paginate',
                    text: i
                })));
            } else {
                $('#' + place + '_pagination').append($('<li class="page-item">').append($('<a>', {
                    class: place.replace('_','-') + '-paginate',
                    text: i
                })));
            }
        }
    } else {
        $('#product_selector_pagination').html('');
        for (let i = 1; i <= totalPage; i++) {
            if (i == selectPage) {
                $('#product_selector_pagination').append($('<li class="page-item active">').append($('<span>', {
                    class: 'product-selector-paginate',
                    text: i
                })));
            } else {
                $('#product_selector_pagination').append($('<li class="page-item">').append($('<a>', {
                    class: 'product-selector-paginate',
                    text: i
                })));
            }
        }
    }
}

//fill product data into select product table
function fillProductData(i, val, currencyFormat) {
    if (val.variants.length > 1) {
        var image = '<td class="pd-image" id="collapse-image-' + val.product_id + '"><img id="product-select-image-' + val.product_id + '" src="' + val.image + '" alt="' + val.title + '"></td>';
        var name = '<td class="pd-title">' + val.title + '</td>';
        var variant = '<td class="pd-variants">' + val.variants.length + ' variants</td>';
        var listVariant = '<td class="pd-action collapse-button" product-id="' + val.product_id + '"><i class="fa fa-chevron-right" aria-hidden="true"></i></td>';
        for (var i = 0; i < val.variants.length; i++) {
            let btn_class = 'add-product';
            let variant_title = val.title;
            if (val.variants[i].selected == 'true' || val.variants[i].out_of_stock == 'true' || parseInt(val.variants[i].price) <= 0) {
                btn_class = 'add-product selected btn btn-success'
            }
            if (val.variants[i].out_of_stock == 'true') {
                variant_title += ' (out of stock)';
            }
            listVariant = listVariant + '<tr class="child-row-' + val.product_id + '" id="select-product-' + val.variants[i].variant_id + '" style="display: none">';
            listVariant = listVariant + '<td class="pd-variants-name">' + val.variants[i].option + '</td><td>' +currencyFormat+' '+ val.variants[i].price + '</td><td class="pd-action"><button type="button" id="add-btn-' + val.variants[i].variant_id + '" class="btn btn-success ' + btn_class + '"' +
                ' variant-id="' + val.variants[i].variant_id + '" ' +
                'product-price="' + val.variants[i].price + '" product-name="' + variant_title + '" variant-title="' + val.variants[i].option + '" product-id="' + val.product_id + '"><i class="fa fa-plus" aria-hidden="true"></i></button></td>';
            listVariant = listVariant + '</tr>';
        }
        var newNode = '<tr>' + image + name + variant + listVariant + '</tr>';
    } else {
        let btn_class = 'add-product';
        let variant_title = val.title;
        if (val.variants[0].selected == 'true' || val.variants[0].out_of_stock == 'true' || parseInt(val.variants[0].price) <= 0) {
            btn_class = 'add-product selected btn btn-success'
        }
        if (val.variants[0].out_of_stock == 'true') {
            variant_title += ' (out of stock)';
        }
        var image = '<td class="pd-image"><img id="product-select-image-' + val.product_id + '" src="' + val.image + '" alt="' + val.title + '"></td>';
        var name = '<td class="pd-title">' + variant_title + '</td>';
        var variant = '<td class="pd-variants collapse-button">'+currencyFormat+' '+ val.variants[0].price + '</td>';
        var listVariant = '<td class="pd-action"><button type="button" id="add-btn-' + val.variants[0].variant_id + '" class="btn btn-success ' + btn_class + '" variant-id="' + val.variants[0].variant_id + '"' +
            ' product-price="' + val.variants[0].price + '" product-name="' + val.title + '" variant-title="' + val.variants[0].option + '" product-id="' + val.product_id + '"><i class="fa fa-plus" aria-hidden="true"></i></button></td>'
        var newNode = '<tr id="select-product-' + val.variants[0].variant_id + '">' + image + name + variant + listVariant + '</tr>';
    }
    $('#product_selector_result').append(newNode);

}

//end

// filter and clear result
function fillSelectedProduct(i, val) {
    var image = '<td class="pd-image"><img src="' + val.image + '" alt="' + val.title + '"></td>';
    var name = '<td class="pd-name">' + val.name + '</td>';
    var title = '<td class="pd-title">' + val.title + '</td>';
    var variant = '<td class="pd-variants">' + val.price + '</td>';
    var listVariant = '<td class="pd-action"><button class="btn btn-default remove-product" type="button" ' + 'variant-id="' + val.variant_id + '"  product-id="' + val.product_id + '"><i class="fa fa-trash" aria-hidden="true"></i></button></td>'
    var newNode = '<tr id="product-selected-row-' + val.variant_id + '" >' + image + name + title + variant + listVariant + '</tr>';
    $('#product_selected_result').append(newNode);
}

function fillSelectedProductOutside(i, val, place = '') {
    // var perPage = 5;
    // for (i =(page-1)*perPage;i<page*perPage;i++){
    var image = '<td class="pd-image"><img src="' + val.image + '" alt="' + val.title + '"></td>';
    var name = '<td class="pd-name">' + val.name + '</td>';
    var title = '<td class="pd-title">' + val.title + '</td>';
    var variant = '<td class="pd-variants">' + currency_format + ' '+val.price + '</td>';
    var newNode = '<tr>' + image + name + title + variant + '</tr>';
    $('#' + place).append(newNode);
}

function clearResult() {
    $('#product_selector_result tbody').empty();
    $('#product_collection').empty();
    $('#product_vendor').empty();
    $('#product_type').empty();
    $('#product_selector_pagination').empty();
    $('#product_selected_result tbody').empty();
    $('#product_collection').append($('<option>', {value: '', text: '-Select Custom Collection-'}));
    $('#product_vendor').append($('<option>', {value: '', text: '-Vendor-'}));
    $('#product_type').append($('<option>', {value: '', text: '-Product Type-'}));
    $('#product_selector_result').append($('<tr>').append($('<td>', {colspan: 4, text: 'Result'})));
    $('#product_selected_result').append($('<tr>').append($('<td>', {colspan: 5, text: 'Selected Result'})));
}

//end

function clearFilter() {
    $('#product_collection').val('');
    $('#product_vendor').val('');
    $('#product_type').val('');
}

//select trigger product logic
$('#trigger-product').click(function () {
    $('#selected_complete').attr('form-type', 'trigger');
    var listSelected = $('#trigger_product_input').val();
    $('#in-progress').show();
    $.ajax({
        url: '/product/getForm',
        type: 'GET',
        data: {
            selected_product: listSelected
        },
        success: function (response) {
            $('#in-progress').hide();
            let collection_filter = response.filterList.custom_collection;
            let product_type = response.filterList.product_type;
            let vendor = response.filterList.vendor;
            let product_list = response.productList;
            let page = response.totalPage;
            let selected_product = response.selectedProduct;
            let currency_format = response.currencyFormat;
            $.each(collection_filter, function (i, val) {
                $('#product_collection').append($('<option>', {value: val.id, text: val.title}));
            });
            $.each(vendor, function (i, val) {
                $('#product_vendor').append($('<option>', {value: val, text: val}))
            });
            $.each(product_type, function (i, val) {
                $('#product_type').append($('<option>', {value: val, text: val}))
            });
            $.each(product_list, function (i, val) {
                fillProductData(i, val,currency_format);
            });
            generatePagination(page, 1);
            if (selected_product != 'none') {
                $.each(selected_product, function (i, val) {
                    // if (i >= 0 && i < 5) {
                        fillSelectedProduct(i, val);
                    // }
                });
            }
        }
    });
});
//end

//select offer product logic
$('#offer-product').click(function () {
    $('#selected_complete').attr('form-type', 'offer');
    var listSelected = $('#offer-product-input').val();
    $('#in-progress').show();
    $.ajax({
        url: '/product/getForm',
        type: 'GET',
        data: {
            selected_product: listSelected
        },
        success: function (response) {
            $('#in-progress').hide();
            let collection_filter = response.filterList.custom_collection;
            let product_type = response.filterList.product_type;
            let vendor = response.filterList.vendor;
            let product_list = response.productList;
            let selected_product = response.selectedProduct;
            let page = response.totalPage;
            let currency_format = response.currencyFormat;
            $.each(collection_filter, function (i, val) {
                $('#product_collection').append($('<option>', {value: val.id, text: val.title}));
            });
            $.each(vendor, function (i, val) {
                $('#product_vendor').append($('<option>', {value: val, text: val}))
            });
            $.each(product_type, function (i, val) {
                $('#product_type').append($('<option>', {value: val, text: val}))
            });
            $.each(product_list, function (i, val) {
                fillProductData(i, val,currency_format);
            });
            generatePagination(page, 1);
            if (selected_product != 'none') {
                $.each(selected_product, function (i, val) {
                    // if (i >= 0 && i < 5) {
                        fillSelectedProduct(i, val);
                    // }
                });
            }
        }
    });
});
//end

//add product to input when select complete
$('#selected_complete').click(function () {
    var selectedProduct = '';
    $('.remove-product').each(function () {
        selectedProduct = selectedProduct + $(this).attr('product-id') + '-' + $(this).attr('variant-id') + ',';
    });
    selectedProduct = selectedProduct.substring(0, selectedProduct.length - 1);
    if ($(this).attr('form-type') == 'trigger') {
        $('#trigger_product_input').val(selectedProduct);
    } else {
        $('#offer-product-input').val(selectedProduct);
    }
    $("#product-modal").modal('hide');
    $('#selected_trigger tbody').empty();
    $('#selected_offer tbody').empty();
    loadSelectedTrigger();
    loadSelectedOffer();
});
//end

//add and remove product
$('#product_selector_wraper').on('click', '.add-product', function () {
    var data = {
        image: $('#product-select-image-' + $(this).attr('product-id')).attr('src'),
        name: $(this).attr('product-name'),
        price: $(this).attr('product-price'),
        product_id: $(this).attr('product-id'),
        title: $(this).attr('variant-title'),
        variant_id: $(this).attr('variant-id'),
    };
    fillSelectedProduct(0, data);
    $(this).addClass('selected');
});
$('#product-selected').on('click', '.remove-product', function () {
    $('#add-btn-' + $(this).attr('variant-id')).removeClass('selected');
    $('#product-selected-row-' + $(this).attr('variant-id')).remove();
});

//end

//filter form
$('#search').click(function () {
    var formdata = $('#product_filter').serialize();
    var selectedProduct = '';
    $('.remove-product').each(function () {
        selectedProduct = selectedProduct + $(this).attr('product-id') + '-' + $(this).attr('variant-id') + ',';
    });
    selectedProduct = selectedProduct.substring(0, selectedProduct.length - 1);
    formdata = formdata + '&selected_product=' + selectedProduct;
    $('#in-progress').show();
    $.ajax({
        url: '/product/filter',
        type: 'POST',
        data: formdata,
        dataType: 'json',
        success: function (response) {
            $('#in-progress').hide();
            $('#product_selector_result tbody').empty();
            var product_list = response.productList;
            $.each(product_list, function (i, val) {
                fillProductData(i, val);
                generatePagination(response.totalPage, 1);
            });
        }
    });
});
//end

// pagination product select form
$('#product_selector_pagination').on('click', 'a.product-selector-paginate', function () {
    var formdata = $('#product_filter').serialize();
    var selectedProduct = '';
    var pageSelected = $(this).text();
    $('.remove-product').each(function () {
        selectedProduct = selectedProduct + $(this).attr('product-id') + '-' + $(this).attr('variant-id') + ',';
    });
    selectedProduct = selectedProduct.substring(0, selectedProduct.length - 1);
    formdata = formdata + '&page=' + pageSelected + '&selected_product=' + selectedProduct;
    $('#in-progress').show();
    $.ajax({
        url: '/product/filter',
        type: 'POST',
        data: formdata,
        dataType: 'json',
        success: function (response) {
            $('#in-progress').hide();
            $('#product_selector_result tbody').empty();
            var product_list = response.productList;
            $.each(product_list, function (i, val) {
                fillProductData(i, val);
            });
            generatePagination(response.totalPage, pageSelected);
        }
    });
});
//end
$('#reset-filter').on('click', function () {
    clearFilter();
})
// clear all result when close modal
$('#product-modal').on('hidden.bs.modal', function () {
    clearResult();
});
//end

// collapse product when have multiple variants
$('#product_selector_result').on('click', '.collapse-button', function () {
    let product_id = $(this).attr('product-id');
    if ($(this).hasClass('active')) {
        $('.child-row-' + product_id).css('display', 'none');
        $('#collapse-image-' + product_id).attr('rowspan', 1);
        $(this).removeClass('active');
    } else {
        let childRowlist = $('.child-row-' + product_id);
        childRowlist.css('display', '');
        $('#collapse-image-' + product_id).attr('rowspan', childRowlist.length + 1);
        $(this).addClass('active');
    }
});
// end

// add logic for date range
var start = moment();
var end = moment().add(7, 'days');

function cb(start, end) {
    $('#start-time').val(start.format('D-M-YYYY'));
    $('#end-time').val(end.format('D-M-YYYY'));
}

$('#date-range').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);
cb(start, end);
$('#date-range').on("focusin", function () {
    $(this).prop('readonly', true);
});
$('#date-range').on("focusout", function () {
    $(this).prop('readonly', false);
});
// end add logic for date range

//offer and trigger field not allow user change
$('#trigger_product_input').on("focusin", function () {
    $(this).prop('readonly', true);
});
$('#trigger_product_input').on("focusout", function () {
    $(this).prop('readonly', false);
});
$('#offer-product-input').on("focusin", function () {
    $(this).prop('readonly', true);
});
$('#offer-product-input').on("focusout", function () {
    $(this).prop('readonly', false);
});
//end

// redirect when discard
$('#discard').click(function (e) {
    e.preventDefault();
    window.location = '/offer/list';
});
//end

//show product info outside modal
$(document).ready(function () {
    loadSelectedTrigger();
    loadSelectedOffer();
});

function loadSelectedTrigger() {
    var listSelectedTrigger = $('#trigger_product_input').val();
    $('#in-progress').show();
    $.ajax({
        url: '/product/getForm',
        type: 'GET',
        data: {
            selected_product: listSelectedTrigger
        },
        success: function (response) {
            $('#in-progress').hide();
            selected_trigger = [];
            for (var i in response.selectedProduct){
                selected_trigger.push(response.selectedProduct[i]);
            }
            currency_format = response.currencyFormat;
            generatePagination(Math.ceil(selected_trigger.length/5), 1,'selected_trigger');
            if (response.selectedProduct != 'none') {
                showSelectedByPage('trigger',1);
            }
        }
    });
}

function loadSelectedOffer() {
    var listSelectedOffer = $('#offer-product-input').val();
    $.ajax({
        url: '/product/getForm',
        type: 'GET',
        data: {
            selected_product: listSelectedOffer
        },
        success: function (response) {
            $('#in-progress').hide();
            selected_offer = [];
            for (var i in response.selectedProduct){
                selected_offer.push(response.selectedProduct[i]);
            }
            currency_format = response.currencyFormat;
            generatePagination(Math.ceil(selected_offer.length/5), 1,'selected_offer');
            if (response.selectedProduct != 'none') {
                showSelectedByPage('offer',1);
            }
        }
    });
}
$('#selected_trigger_pagination').on('click', 'a.selected-trigger-paginate', function () {
    var pageSelected = $(this).text();
    showSelectedByPage('trigger',pageSelected);
    generatePagination(Math.ceil(selected_trigger.length/5), pageSelected,'selected_trigger');
});

$('#selected_offer_pagination').on('click', 'a.selected-offer-paginate', function () {
    var pageSelected = $(this).text();
    showSelectedByPage('offer',pageSelected);
    generatePagination(Math.ceil(selected_offer.length/5), pageSelected,'selected_offer');
});

function showSelectedByPage(type, page) {
    if (type === 'trigger') {
        $('#selected_trigger').empty();
        $.each(selected_trigger, function (i, val) {
            if (i >= (page - 1) * 5 && i <= (page * 5) - 1) {
                fillSelectedProductOutside(i, val, 'selected_trigger');
            }
        });
    } else {
        $('#selected_offer').empty();
        $.each(selected_offer, function (i, val) {
            if (i >= (page - 1) * 5 && i <= (page * 5) - 1) {
                fillSelectedProductOutside(i, val, 'selected_offer');
            }
        });
    }
}
