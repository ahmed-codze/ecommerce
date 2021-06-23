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
            $('.fixed-nav').slideDown('slow')
        } else {
            $('.fixed-nav').slideUp(1)
        }
    })

    //carousel 
    if ($(window).width() > 500) {
    $('.main-carousel .carousel-inner img').height($(window).height() - $('nav').height());
    } else {
        $('.main-carousel .carousel-inner img').height( ( $(window).height() - $('nav').height() ) / 1.5);
    }

    // products swiper

    const swiper = new Swiper('.swiper-container', {
        // Optional parameters
        loop: true,
        slidesPerView: 1,
        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },
      
        // Navigation arrows
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {  
            320: {
                slidesPerView: 1,
                spaceBetween: 20
              },
              // when window width is >= 480px
              480: {
                slidesPerView: 2,
                spaceBetween: 30
              },
              // when window width is >= 640px
              800: {
                slidesPerView: 3,
                spaceBetween: 20
              },
              1000: {
                slidesPerView: 4,
                spaceBetween: 20
              }
            },
        
        // And if we need scrollbar
        scrollbar: {
          el: '.swiper-scrollbar',
        },
      });
}); 

