$('.popup-overflow').click(function(e) {
    if(blocked) return;
    if(e.target.classList.contains('show')) {
        $(this).removeClass('show');
        $("#popup").html(null);
    }
});
$(".login-page .btn-right").click(function() {
    ajax("register.html");
});
$(".login-page .btn-left").click(function() {
    ajax("login.html")
});