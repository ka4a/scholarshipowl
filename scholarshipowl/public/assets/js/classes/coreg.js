$(document).on('change', 'input[name="coregs[Berecruited][checked]"]', function () {
    if($('#berecruited-yes').prop('checked')) {
        $('.berecruited-container').slideDown();
        $('.berecruited-container').find('input').attr('required');
    }
    else if ($('#berecruited-no').prop('checked')) {
        $('.berecruited-container').slideUp();
        $('.berecruited-container').find('input').removeAttr('required');
    }
});
