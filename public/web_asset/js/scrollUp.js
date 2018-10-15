$(function(){
    var scrolltop = $("#scrollup");
    $(window).scroll(function(){
        $(this).scrollTop() > 300 ? scrolltop.show() : scrolltop.hide();
    });
    scrolltop.click(function(){
        $("html,body").animate({scrollTop:0},1000);
    });
});