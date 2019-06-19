

$(function () {
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass   : 'iradio_flat-green'
    });
    $('.add-to-checkout-event > label, .add-to-checkout-event .iCheck-helper').click(function() {
        $('#Label_offer_type_cross').prop('checked',true).iCheck('update');
        $('#Label_offer_type_up').prop('checked',false).iCheck('update');
        $('#Label_offer_type_up').iCheck('disable');
        $('#Label_offer_type_up').parents('.cross-sell-type').addClass('disable');
    });
    $('.add-to-cart-event > label, .add-to-cart-event .iCheck-helper').click(function() {
        $('#Label_offer_type_up').iCheck('enable');
        $('.cross-sell-type').removeClass('disable');
    });
})