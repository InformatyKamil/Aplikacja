$("#register-form").submit(function(event){
    blocked = true;
    event.preventDefault();
    if (request) {
        request.abort();
    }
    $('.loader-2').css('display','flex');
    let $form = $(this);
    let $inputs = $form.find("input, select, button, textarea");
    let serializedData = $form.serialize();
    $inputs.prop("disabled", true);

    request = $.ajax({
        url: "register.php",
        type: "post",
        data: serializedData
    });
    request.done(function (response, textStatus, jqXHR){
        let isOk = response.startsWith('Poprawnie');
        let clas = isOk ? 'correct' : 'error';
        blocked = false;
        $('.loader-2').css('display','none');
        $('.small-popup').text(response).addClass(clas).addClass('after');
        if(isOk) {
          $('.popup-overflow').removeClass('show');
          $("#popup").html(null);
        }
        setTimeout(()=> {
          $('.small-popup').text(null).removeClass('after').removeClass(clas);
        },1500);
    });
    request.fail(function (jqXHR, textStatus, errorThrown){
      $('.small-popup').text(errorThrown).addClass('error').addClass('after');
      $('.loader-2').css('display','none');
      $('.popup-overflow').removeClass('show');
        setTimeout(()=> {
          $('.small-popup').text(null).removeClass('after').removeClass('error');
        },1500);
        console.error("The following error occurred: "+textStatus, errorThrown);
    });
    request.always(function () {
        $inputs.prop("disabled", false);
    });
});