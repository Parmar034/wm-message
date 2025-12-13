$(document).ready(function () {

    $('.post-carousel').owlCarousel({
        loop:true,
        margin:15,
        nav: true,
        dot:false,
        autoplay: false,
        slideTransition: 'linear',
        autoplayTimeout: 0,
        autoplaySpeed: 30000,
        autoplayHoverPause: true,
        navRewind: true,
        mouseDrag: false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:2
            }
        }
    });

    $('.gallery-carousel').owlCarousel({
        loop:true,
        margin:24,
        nav: true,
        dot:false,
        autoplay: false,
        slideTransition: 'linear',
        autoplayTimeout: 0,
        autoplaySpeed: 30000,
        autoplayHoverPause: true,
        responsive:{
            0:{
                items:1,
                margin: 24,
            },
            600:{
                items:2,
                margin: 24,
            },
            1000:{
                items:3,
                margin: 24,
            }
        }
    })

});	

$(document).ready(function(){
    window.onscroll = function() {myFunction()};
                
    var header = document.getElementById("header");
    var sticky = header.offsetTop;
    
    function myFunction() {
      if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    }
    
});


$(document).ready(function(){
     $( ".owl-prev").html('<i class="fa fa-long-arrow-left"></i>');
     $( ".owl-next").html('<i class="fa fa-long-arrow-right"></i>');
}); 


var width = jQuery(window).width();

jQuery('.navbar-toggler').on('click', function() {
    if (jQuery('.menu').hasClass("show")) {
        jQuery('.menu').removeClass('show');
    } else {
        jQuery('.menu').addClass('show');
    }
});



// Get the modal

var modalparent = document.getElementsByClassName("modal_link");

// Get the button that opens the modal

var modal_btn_multi = document.getElementsByClassName("btn_link");

// Get the <span> element that closes the modal
var span_close_multi = document.getElementsByClassName("close_link");

// When the user clicks the button, open the modal
function setDataIndex() {

for (i = 0; i < modal_btn_multi.length; i++)
    {
        modal_btn_multi[i].setAttribute('data-index', i);
        modalparent[i].setAttribute('data-index', i);
        span_close_multi[i].setAttribute('data-index', i);
    }
}



for (i = 0; i < modal_btn_multi.length; i++)
    {
        modal_btn_multi[i].onclick = function() {
        var ElementIndex = this.getAttribute('data-index');
        modalparent[ElementIndex].style.display = "block";
    };

    // When the user clicks on <span> (x), close the modal
    span_close_multi[i].onclick = function() {
        var ElementIndex = this.getAttribute('data-index');
        modalparent[ElementIndex].style.display = "none";
    };

}

window.onload = function() {
setDataIndex();
};

window.onclick = function(event) {
if (event.target === modalparent[event.target.getAttribute('data-index')]) {
    modalparent[event.target.getAttribute('data-index')].style.display = "none";
    }

// OLD CODE
if (event.target === modal) {
    modal.style.display = "none";
}
};

$('.popup-gallery').magnificPopup({
    delegate: 'figure .gallery-item a',
    type: 'image',
    removalDelay: 500, //delay removal by X to allow out-animation
    callbacks: {
        beforeOpen: function() {
            // just a hack that adds mfp-anim class to markup 
             this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
             this.st.mainClass = this.st.el.attr('data-effect');
        },
    },
    tLoading: 'Loading image #%curr%...',
    mainClass: 'mfp-img-mobile',
    gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1], // Will preload 0 - before current, and 1 after the current image
        arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
    },
    image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        titleSrc: function(item) {
            return item.el.attr('title') + '<small></small>';
        }
    }
});


// $(document).ready(function(){
// // Show the first tab and hide the rest
// $('.menu ul li:first-child').addClass('active');

// // Click function
// $('.menu ul li').click(function(){
//   $('.menu ul li').removeClass('active');
//   $(this).addClass('active');
// });
// });
$(document).ready(function () {

    $('.gallery-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav: true,
    dot:false,
    autoplay: false,
    slideTransition: 'linear',
    autoplayTimeout: 0,
    autoplaySpeed: 30000,
    autoplayHoverPause: true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:3
        }
    }
})

});	
$(document).ready(function(){


    // Click function
    $('.menu ul li').click(function(){
      $('.menu ul li').removeClass('active');
      $(this).addClass('active');
    });
    });
    
    $(document).ready(function(){
    $('#myVideo').click(function(){
        $(this)
            .hide()
            .siblings('#thumbnail1')
            .show();
        });
    
        $('#thumbnail1').click(function(){
        $(this)
            .hide()
            .siblings('#myVideo')
            .show();
        });
    
        $("#thumbnail1").click(function(){
            $("#thumbnail").hide();
        });
      
    });
    
    
    
        // Show the first tab and hide the rest
        $('#details-tab-item li:first-child').addClass('active');
        $('.details-tab-details').hide();
        $('.details-tab-details:first').show();
        
        // Click function
        $('#details-tab-item li').click(function(){
          $('#details-tab-item li').removeClass('active');
          $(this).addClass('active');
          $('.details-tab-details').hide();
          
          var activeTab = $(this).find('a').attr('href');
          $(activeTab).fadeIn();
          return false;
        });
    
    
    
    
    
        jQuery(document).ready(function () {
    
            //Pagination JS
            //how much items per page to show
            var show_per_page = 1; 
            //getting the amount of elements inside pagingBox div
            var number_of_items = $('#pagingBox').children().size();
            //calculate the number of pages we are going to have
            var number_of_pages = Math.ceil(number_of_items/show_per_page);
            
            //set the value of our hidden input fields
            $('#current_page').val(0);
            $('#show_per_page').val(show_per_page);
            
            //now when we got all we need for the navigation let's make it '
            
            /* 
            what are we going to have in the navigation?
                - link to previous page
                - links to specific pages
                - link to next page
            */
            var navigation_html = '<a class="previous_link" href="javascript:previous();"><i class="fa fa-chevron-left"></i></a>';
            var current_link = 0;
            while(number_of_pages > current_link){
                navigation_html += '<a class="page_link" href="javascript:go_to_page(' + current_link +')" longdesc="' + current_link +'">'+ (current_link + 1) +'</a>';
                current_link++;
            }
            navigation_html += '<a class="next_link" href="javascript:next();"><i class="fa fa-chevron-right"></i></a>';
            
            $('#page_navigation').html(navigation_html);
            
            //add active_page class to the first page link
            $('#page_navigation .page_link:first').addClass('active_page');
            
            //hide all the elements inside pagingBox div
            $('#pagingBox').children().css('display', 'none');
            
            //and show the first n (show_per_page) elements
            $('#pagingBox').children().slice(0, show_per_page).css('display', 'block');
        
    });
    
    
    
    
    
    //Pagination JS
    
    function previous(){
        
        new_page = parseInt($('#current_page').val()) - 1;
        //if there is an item before the current active link run the function
        if($('.active_page').prev('.page_link').length==true){
            go_to_page(new_page);
        }
        
    }
    
    function next(){
        new_page = parseInt($('#current_page').val()) + 1;
        //if there is an item after the current active link run the function
        if($('.active_page').next('.page_link').length==true){
            go_to_page(new_page);
        }
        
    }
    function go_to_page(page_num){
        //get the number of items shown per page
        var show_per_page = parseInt($('#show_per_page').val());
        
        //get the element number where to start the slice from
        start_from = page_num * show_per_page;
        
        //get the element number where to end the slice
        end_on = start_from + show_per_page;
        
        //hide all children elements of pagingBox div, get specific items and show them
        $('#pagingBox').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');
        
        /*get the page link that has longdesc attribute of the current page and add active_page class to it
        and remove that class from previously active page link*/
        $('.page_link[longdesc=' + page_num +']').addClass('active_page').siblings('.active_page').removeClass('active_page');
        
        //update the current page input field
        $('#current_page').val(page_num);
    }


    $(document).ready(function() {

        $('a.btn-gallery').on('click', function(event) {
            event.preventDefault();
            
            var gallery = $(this).attr('href');
    
            $(gallery).magnificPopup({
                delegate: 'a',
                type:'image',
                gallery: {
                    enabled: true,
                    nav: true,
                    dot:false,
                    navigateByImgClick: true,
                    preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                }
            }).magnificPopup('open');
        });
    
    });
    
$(document).ready(function(){
$('#myVideo').click(function(){
    $(this)
        .hide()
        .siblings('#thumbnail1')
        .show();
    });

    $('#thumbnail1').click(function(){
    $(this)
        .hide()
        .siblings('#myVideo')
        .show();
        $('.main-img').hide();
    });

    // $("#thumbnail1").click(function(){
    //     $("#thumbnail").hide();
    // });
  
});












