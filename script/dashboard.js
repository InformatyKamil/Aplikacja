$('._subnav-link').each(function(){
    $(this).click(function(e) {
        e.preventDefault();
    });
});

$('.dashboard-page .hamburger-menu').click(function() {
    document.querySelector(".dashboard-page > div").classList.toggle("change");
});

const setMin = (uruchomienie,zakonczenie) => {
    $(zakonczenie).attr('readonly','readonly');
    $(uruchomienie).change(it => {
        $(zakonczenie).removeAttr('readonly');
        $(zakonczenie).attr('min',it.target.value);
    })
}

$(".dashboard-page ._nav-list").click(function(e) {
    e.preventDefault();
    const el = e.target.parentNode;
    if (!el.classList.contains('no-menu') && el.classList[0] === "_nav-link") {
      el.nextElementSibling.classList.toggle("change");
      el.classList.toggle("change");
    } else {
        let isLogut = el.href.indexOf("login") !== -1;
        if(isLogut) {
            get(el.href, (response) => logOut(response),() => empty());
        } else {
            baseUrl = el.href;
            get(baseUrl, (response) => loadData(response), () => getAfter()); 
        }
    }
});