document.addEventListener('DOMContentLoaded', function() {
    // Cookies Section
    const cookiesSection = document.querySelector('.cookies-section');
    const cookiesCloseButton = document.querySelector('.cookies-btn');

    if (cookiesSection && cookiesCloseButton) {
        if (localStorage.getItem('cookiesConsent') === 'true') {
            cookiesSection.classList.add('hide');
        }

        cookiesCloseButton.addEventListener('click', function() {
            cookiesSection.classList.add('hide');
            localStorage.setItem('cookiesConsent', 'true');
        });
    }

    // Header Top Section
    const headerTopSection = document.querySelector('.header-top-section');
    const headerCloseButton = document.querySelector('.close-welcome-text');

    if (headerTopSection && headerCloseButton) {
        if (localStorage.getItem('headerClosed') === 'true') {
            headerTopSection.classList.add('hide');
        }

        headerCloseButton.addEventListener('click', function() {
            headerTopSection.classList.add('hide');
            localStorage.setItem('headerClosed', 'true');
        });
    }
});

$(document).ready(function(){
    $('.pdf-logo-icon').owlCarousel({
        nav: true,
        margin: 10,
        navText: [
            '<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>',
            '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'
        ],
        loop: false,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 4
            },
            1000: {
                items: 6
            }
    }
    });
});



$('.banner-carousel, .testimonial-carousel').owlCarousel({
    nav:      false,
    navText:  false,
    margin:   0,
    loop:     true,
    autoplay: true,
    responsive:{
        0:{
                items:1
        },
        480:{
                items:1
        },
        768:{
                items:1
        }
    }
});


 
 $(document).ready(function () {


    $('.desktop_screen .shop-box-icon-carousel').owlCarousel({
        nav:      true,
        navText:  ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
        margin:   10,
        loop:     false,
        autoplay: true,
        responsiveClass: true,
        responsive:{
            0:{
                    items: 3
            },
            480:{
                    items:3
            },
            768:{
                    items:4
            },
            1000:{
                items:6
            }
        }
    });
});

$(document).ready(function () {


    $('.mobile_screen .shop-box-icon-carousel').owlCarousel({
        nav:      true,
        navText:  ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"  id="show-more"/>'],
        margin:   10,
        loop:     false,
        autoplay: true,
        responsiveClass: true,
        responsive:{
            0:{
                    items: 3
            },
            480:{
                    items:3
            },
            768:{
                    items:4
            },
            1000:{
                items:6
            }
        }
    });
});

$(document).ready(function(){
    $('#show-more').on('click', function() {
        $('#initial-items').addClass('hidden');
        $('#all-items').removeClass('hidden');
        $('#all-items').owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            navText:  ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow" />'],
            lazyLoad: true,
            responsive: {
                0: {
                    items: 3
                },
                600: {
                    items: 5
                },
                1000: {
                    items: 8
                }
            }
        });
    });
});








$(document).ready(function(){
$('.product-logo-icon-carousel').owlCarousel({
    nav:      true,
    margin: 10,
    navText:  [ '<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>' ],
    loop:     false,
    autoplay: false,
    responsiveClass: true,
    responsive:{
        0:{
                items:4
        },
        375: {
                items: 4
        },
        480:{
                items:4
        },
        768:{
                items:2
        },
        1000:{
            items:6
        }
    }
});
});

$(document).ready(function(){
$('.product-carousel').owlCarousel({
    nav: true,
        margin: 10,
        navText: [
            '<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>',
            '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'
        ],
        loop: false,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2
            },
            375: {
                items: 2
            },
            600: {
                items: 2
            },
            768:{
                items:3
            },
            1000: {
                items: 4
            }
    }
});
});



window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}


$('.testimonial-carousel').owlCarousel({
        nav:      true,
        margin: 0,
        navText:  [ '<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>' ],
        loop:     false,
        autoplay: true,
        responsive:{
            0:{
                    items:1
            },
            480:{
                    items:1
            },
            768:{
                    items:1
            }
        }
    });



    // Show or hide the back-to-top button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('#back-top').fadeIn();
        } else {
            $('#back-top').fadeOut();
        }
    });

    // Animate the scroll to top
    $('#back-top').click(function(){
        $('html, body').animate({scrollTop : 0}, 800);
        return false;
    });
    $(document).ready(function(){
            
        $('#specialize-tab li:first-child').addClass('active_li');
        $("#specialize-tab li").click(function(){
            
        $("#specialize-tab li.active_li").removeClass("active_li");
        $(this).addClass("active_li")})

});



function openForm(evt, formName) {
    var i, tabcontent, tablinks;
    // Hide all elements with class "tabcontent" or "tabcontent_popup"
    tabcontent = document.querySelectorAll(".tabcontent, .tabcontent_popup");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    // Remove "active" class from all elements with class "tablinks" or "tablinks_popup"
    tablinks = document.querySelectorAll(".tablinks, .tablinks_popup");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    // Display the selected form
    document.getElementById(formName).style.display = "block";
    // Add "active" class to the clicked tab
    evt.currentTarget.classList.add("active");
}
// Open default tab on page load
//document.getElementById("defaultOpen").click();


