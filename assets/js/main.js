$(document).ready(function(){

    // dropdown menu 

    $(".dropdown").hover(function(){
        var dropdownMenu = $(this).children(".dropdown-menu");
        if(dropdownMenu.is(":visible")){
            dropdownMenu.parent().toggleClass("open");
        }
    });

    // search bar 
    $('.search-bar').hide();
    $('.search-icon').click(function () {
        $('.search-bar').slideToggle("slow");
        
    });

    // navbar

    $(window).scroll(function () {
        if($(window).scrollTop() > 300) {
            $('nav').addClass('fixed-top')
            $('nav').slideDown('slow');
        } else {
            $('nav').removeClass('fixed-top')
        }
    })

    //carousel 
    if ($(window).width() > 500) {
    $('.main-carousel .carousel-inner img').height($(window).height() - $('nav').height());
    } else {
        $('.main-carousel .carousel-inner img').height( ( $(window).height() - $('nav').height() ) / 1.5);
    }
}); 

