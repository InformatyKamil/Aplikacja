const empty = () => {};
const loadData = (response) =>  $('.main').html(null).append($("<div class='main-overwlow'></div>").append(response));
const get = (href,cb,doneCb) => $.get( href, cb).done(doneCb);
const post = (href,dane,doneCb,needAdd = false) => {
    let additional = {
        processData: false,
        contentType: false
    }
    let obj = needAdd ? {
        url: href,
        type: "post",
        data: dane,
        ...additional
    } : {
        url: href,
        type: "post",
        data: dane
    }
    $.ajax(obj).done(doneCb);
}
const ajax = (where) => {
    $.ajax({
        url: where, 
        context: document.body,
        success: function(response) {
            $("#popup").html(response);
        }
    }).done(function() {
        $('.popup-overflow').addClass('show');
    });
}
const getLoad = (response) => {
    if(response.length < 120) {
        showError(response,false,"error");
    } else {
        loadData(response);
    }
}

const loadSearch = (response) => {
    $('.table ').html(null).append(response);
} 
const serializeData = (name,url) => {
    let form = $(name);
    if(form[0].checkValidity()) {
        let $inputs = form.find("input, select, button, textarea");
        let serializedData = form.serialize();
        $inputs.prop("disabled", true);
        return `${serializedData}&${url}=true`;
    } else {
        form[0].reportValidity();
    }
}

const setCena = (where,cena,isPz) => {
    $(where).on("input", function() {
        if(price == null) return;
        if(!isPz && max-$(this).val() < 0 ) return;
        if($(this).val() == "")  {
            $(cena).val(0);
        }
        if(!isPz) {
            if($(this).val() == "") {
                $('#stan-not_nessesary').val(max); 
            } else {
                $('#stan-not_nessesary').val(parseFloat(max)-parseFloat($(this).val())); 
            }
        } else {
            if($(this).val() == "") {
                $('#stan-not_nessesary').val(max); 
            }else {
                $('#stan-not_nessesary').val(parseFloat (max)+parseFloat($(this).val())); 
            }
        }
        $(cena).val(($(this).val() * price));
     });
}

const search = (select,input) => {
    let selected,interval = null;
    $(input).prop("disabled", true);
    $(select).on('change', (e) => {
        
        if(e.target.value.includes("Szukaj")) {
            $(input).prop("disabled", true);
            selected = null;
        } else {
            $(input).prop("disabled", false);
            selected = e.target.value;
        }
    });
    $(input).on("input",(e) => {
        let value = e.target.value.trim();
        clearInterval(interval);
        if(selected == null) return;
        interval = setTimeout(() => {
            let href = `${fullUrl}/kontrahenci.php?field=${selected}&value=${value == "" ? "all" : value}`;
            get(href,(response) => loadSearch(response),() => getAfter());
        },300);
    }); 
};

const reset = () => {
    $('#cena-not_nessesary').val(null);
    $('#stan-not_nessesary').val(null);
    $("#cena_zakupu_netto").val(null);
}

const getPrice = (where,isPz) => {
    $('.ilosc').prop("disabled", true);
    $("#id_towar").on('change', (e) => {
        let value = e.target.value;
        price = 0;
        max = 0;
        reset();
        if(value == "")  {
            $('.ilosc').val(null).prop("disabled", true);
            return;
        }
        $('.ilosc').val(null).prop("disabled", false);
        get(`${fullUrl}/${where}.php?price=${value}`,(response) => {
            let res = response.split(",");
            price = res[0];
            max = res[1];
            $('#cena-not_nessesary').val(price);
            $('#stan-not_nessesary').val(max);
            if(!isPz) {
                $('#ilosc_WZ').attr('max',max).next().text(`Maksymalna mozna kupić ${max} sztuk`); 
            }
        } );
      });
}
const setTowar = (where) => {
    $('#id_towar').prop("disabled", true);
    $('#id_kontrahent').on('change', (e) => {
        let value = e.target.value;
        $('#id_towar').empty();
        reset();
        $('.ilosc').val(null).prop("disabled", true);
        if(value == "")  {
            $('#id_towar').prop("disabled", true);
            return;
        }
        $('#id_towar').prop("disabled", false);
        get(`${fullUrl}/${where}.php?idKontrahent=${value}`,(response) => {
            $('#id_towar').append(response);
        } );
    });
}

const disableEnableButtons = (form,enable) => {
    let inputs = form.find("input, select, button, textarea");
    inputs.prop("disabled", enable);
} 

const hide = (time,isOk,clas= null,where = null) => {
    setTimeout(()=> {
        $('.small-popup').text(null).removeClass('after').removeClass(clas);
        if(isOk) {
          window.location.href=where;
        }
      },time);
}
const showError = (error,isOk,clas,href = null) => {
    $('.small-popup').text(error).addClass(clas).addClass('after');
    hide(1500,isOk,clas,href);
}

const logOut = (response) => {
    let splited = response.split(",");
    let isOk = splited[0] != "0"
    let clas = isOk ? 'correct' : 'error';
    showError(splited[1],isOk,clas,`${fullUrl}/login-page.php`);
}

const getAfter = () => {

    $('#file').on('change', (e) => {
        const formData = new FormData();
        const term = document.getElementById("id_terminarz").value;
        formData.append('files', $('#file')[0].files[0]);
        post(`${fullUrl}/terminarz.php?saveFile=true&terminarz=${term}`, formData,(res) => {
            document.querySelector('#name-file').textContent = `Dodano wcześniej plik o nazwie: ${res}`;
        },true);
    });

    $('.main button:not(.donwload-button)').click(function(e) {
        let href = e.target.dataset.href;
        e.preventDefault();
        if(href.includes("delete")) {
            get(href,(response) => getLoad(response),() => getAfter());
        }
        else if(href.includes("show")) {
            get(href,(response) => getLoad(response),() => getAfter());
        }
        else if(href.includes("update")) {
            get(href,(response) => getLoad(response),() => getAfter());
        } 
        else if(href.includes("new")) {
            get(href,(response) => getLoad(response),() => getAfter());
        }
        else if(href.includes("save") || href.includes("add")) {
            let url = href.includes("save") ? "save" : "add";
            let form = $("#update-form");
            let serialize = serializeData(form,url);
            if(serialize) {
                post(href,serialize,(res) => {
                    if(res.length < 120) {
                        if(res.endsWith("wylogowany")) {
                            logOut(`1,${res}`); 
                        } 
                        showError(res,false,"error");
                        disableEnableButtons(form,false);
                    } else {
                        get(baseUrl,(response) => getLoad(response),() => getAfter())
                    }
                });
            }
        } else {
            showError("Błąd w trakcie requestu",false,"error");
        } 
    });
}