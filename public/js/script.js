$(document).ready(function(){
    $(".open-btn").click(function(){
        $(".navbar_responsive").css("display", "flex");
        $(".open-btn").css("display", "none");
        $("header").css("background-color", "rgb(231, 202, 151)");
        $("header").css("justify-content", "center");
        $(".header_blockleft").css("display", "none");
    })

    $(".close-btn").click(function(){
        $(".navbar_responsive").css("display", "none");
        $(".open-btn").css("display", "flex");
        $(".header_blockleft").css("display", "flex");

    })

    $(".navbar_responsive li").click(function(){
        $(".navbar_responsive").css("display", "none");
        $(".open-btn").css("display", "flex");
    })

})