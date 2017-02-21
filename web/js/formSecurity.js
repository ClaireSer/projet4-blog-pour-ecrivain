$(function () {
    
    var passwordFirst = $("#user_password_first");
    var passwordSecond = $("#user_password_second");

    $("div.container").onclick( function () {
            alert("attention!");
            $("h1").css('color', 'blue');
        
        if (passwordFirst.val() !== passwordSecond.val()) {
            alert("attention!");
        }
    });    
})
