// Helper function
let domReady = (cb) => {
    document.readyState === 'interactive' || document.readyState === 'complete'
        ? cb()
        : document.addEventListener('DOMContentLoaded', cb);
};

domReady(() => {
    // Display body when DOM is loaded
    document.body.style.visibility = 'visible';
});

$(".file-upload-btn").click(function () {
    $('.file-upload-input').trigger('click');
});

$(".file-upload-input").change(function () {
    readURL(this);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        console.log(input.files);

        var reader = new FileReader();

        reader.onload = function (e) {
            $('.file-upload-image').attr('src', e.target.result);
            $(".remove-image").show();
            $('.image-title').val(input.files[0].name).parent('.form-group').addClass('focused');
        };

        reader.readAsDataURL(input.files[0]);

    } else {
        removeUpload();
    }
}

function removeUpload() {
    $('.image-title').val("").parent('.form-group').removeClass('focused');
}
$(".remove-image").click(function () {
    removeUpload();
    $(this).hide();
});


function fun_scribe_email() {

    var email = $('#text_subscribe_email').val();

    var flag = 0;
    if (email == '' || email == null) {
        flag++;
        Swal.fire({
            title: "Oops...",
            text: "Invalid your email Address!",
            icon: "error",
            buttonsStyling: false,
            confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
        });
    }

    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (email.match(mailformat)) {
        $("#div_email").removeClass('has-error');
        $("#msg_email").html("");
    } else {
        flag++;
        Swal.fire({
            title: "Oops...",
            text: "Invalid your email Address!",
            icon: "error",
            buttonsStyling: false,
            confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
        });
    }

    if (flag == 0) {

        $.ajax({
            url: subscribeemail,
            data: {
                'email': email,
                '_token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: 'POST',
            success: function (result) {
                if (result.success == 'TRUE') {
                    Swal.fire({
                        title: "Success",
                        text: "Successfully Subscribe email",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                    $('#text_subscribe_email').val('');
                } else {
                    Swal.fire({
                        title: "Oops...",
                        text: result.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                }
            }
        });
    }
}
function share_model(id, type) {

    var token = $("meta[name='csrf-token']").attr("content");
    var id = id;

    $.ajax({
        url: sharemodel,
        type: 'POST',
        data: {
            _token: token,
            id: id,
            type: type,
        },
        success: function (response) {
            $('#share_popup').modal('show');
            $("#product_images").attr("src", response.image_path);
            $("#product_name").text(response.name);
            $('#share_type').html(response.type);
            if (type == 'product') {
                var slug_type = 'products/';
            }
            if (type == 'catalogs') {
                var slug_type = 'catalogue/';
            }
            if (type == 'collection') {
                var slug_type = 'collection/';
            }
            if (type == 'product-page') {
                var slug_type = 'products';
            }
            if (type == 'current-url') {
                var current_url = $(location).prop('href');
                var w_url = 'https://api.whatsapp.com/send?text=' + current_url;
                $('.whatsapp_share').attr('onclick', "window.open('" + w_url + "', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var f_url = 'https://www.facebook.com/sharer/sharer.php?u=' + current_url;
                $('.facebook_share').attr('onclick', "window.open('" + f_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var t_url = 'https://twitter.com/intent/tweet?url=' + current_url;
                $('.twitter_share').attr('onclick', "window.open('" + t_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var i_url = 'https://www.instagram.com/';
                $('.insta_share').attr('onclick', "window.open('" + i_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var c_url = current_url;
                $('.copy_link_share').attr('onclick', "copyLink('" + c_url + "')");
            } else if (type == 'product-page') {
                var w_url = 'https://api.whatsapp.com/send?text=' + BASE_URL + '' + slug_type;
                $('.whatsapp_share').attr('onclick', "window.open('" + w_url + "', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var f_url = 'https://www.facebook.com/sharer/sharer.php?u=' + BASE_URL + '' + slug_type;
                $('.facebook_share').attr('onclick', "window.open('" + f_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var t_url = 'https://twitter.com/intent/tweet?url=' + BASE_URL + '' + slug_type;
                $('.twitter_share').attr('onclick', "window.open('" + t_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var i_url = 'https://www.instagram.com/';
                $('.insta_share').attr('onclick', "window.open('" + i_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var c_url = BASE_URL + slug_type;
                $('.copy_link_share').attr('onclick', "copyLink('" + c_url + "')");
            } else {
                var w_url = 'https://api.whatsapp.com/send?text=' + BASE_URL + '' + slug_type + '' + response.slug;
                $('.whatsapp_share').attr('onclick', "window.open('" + w_url + "', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var f_url = 'https://www.facebook.com/sharer/sharer.php?u=' + BASE_URL + '' + slug_type + '' + response.slug;
                $('.facebook_share').attr('onclick', "window.open('" + f_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var t_url = 'https://twitter.com/intent/tweet?url=' + BASE_URL + '' + slug_type + '' + response.slug;
                $('.twitter_share').attr('onclick', "window.open('" + t_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var i_url = 'https://www.instagram.com/';
                $('.insta_share').attr('onclick', "window.open('" + i_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var c_url = BASE_URL + slug_type + response.slug;
                $('.copy_link_share').attr('onclick', "copyLink('" + c_url + "')");
            }
        },
    });
}
function copyLink(value) {
    const el = document.createElement('input');
    el.value = value;
    document.getElementById('copy_share').appendChild(el);
    el.select();
    document.execCommand('copy');
    document.getElementById('copy_share').removeChild(el);

    Swal.fire({
        icon: 'success',
        title: 'Link copied successfully.',
        text: ' ',
        buttons: false,
        timer: 1500
    });
    return false;
}

function addToWishlist(bpr_id, product_title, slug, product_image, price) {

    var token = $("meta[name='csrf-token']").attr("content");
    var id = bpr_id;
    var name = product_title;
    var slug = slug;
    var image = product_image;
    var price = price;
    $.ajax({
        url: addwishlst,
        type: 'POST',
        data: {
            'id': id,
            'name': name,
            'slug': slug,
            'image': image,
            'price': price,
            '_token': token
        },
        success: function (responseOBJ) {
            if (responseOBJ.status == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Product Successfully Added to Wishlist.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });

                $("#whishlist_count").html('(' + responseOBJ.count + ')');
                $("#whishlist_count_mob").html('(' + responseOBJ.count + ')');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error In Adding Product to Wishlist.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });
            }
        }
    });
}

function addToCart(pro_id, product_title, price, index, buy_now) {

    var qty = $('#product_qty').val();
    if (qty != '' && qty != null) {
        qty = qty;
    } else {
        qty = 1;
    }
    var pro_id = pro_id;
    var pr_bpr_id = pro_id;
    var bpr_pr_title = product_title;
    var bpr_price = price;
    $.ajax({
        url: addtocrt,
        type: 'POST',
        data: {
            'pro_id': pro_id,
            'qty': qty,
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (responseOBJ) {
            if (responseOBJ.status == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Product Successfully Added to Cart.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });

                $("#cart_count").html('(' + responseOBJ.count + ')');
                $("#cart_count_mob").html('(' + responseOBJ.count + ')');
                if (buy_now && buy_now == 'buynow') {
                    window.location.href = front_cart;
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error In Adding Product to Cart.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });
            }
        }
    });

}

document.addEventListener('DOMContentLoaded', function () {
    // Cookies Section
    const cookiesSection = document.querySelector('.cookies-section');
    const cookiesCloseButton = document.querySelector('.cookies-btn');

    if (cookiesSection && cookiesCloseButton) {
        if (localStorage.getItem('cookiesConsent') === 'true') {
            cookiesSection.classList.add('hide');
        }

        cookiesCloseButton.addEventListener('click', function () {
            cookiesSection.classList.add('hide');
            localStorage.setItem('cookiesConsent', 'true');
        });
    }

    // Header Top Section
    const headerTopSection = document.querySelector('.header-top-section');
    const headerCloseButton = document.querySelector('.close-welcome-text');
    alert(localStorage.getItem('headerClosed'));

    if (headerTopSection && headerCloseButton) {
        if (localStorage.getItem('headerClosed') === 'true') {
            headerTopSection.classList.add('hide');
            $('.banner-slider-section').removeClass('header-note');
            $('.margin-top-head').removeClass('header-note');
            //closeNotification();
        } else {
            openNotification();
        }

        headerCloseButton.addEventListener('click', function () {
            headerTopSection.classList.add('hide');
            localStorage.setItem('headerClosed', 'true');
            $('.banner-slider-section').removeClass('header-note');
            $('.margin-top-head').removeClass('header-note');
        });
    }
});



$(document).ready(function () {

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
                items: 1
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
    nav: false,
    navText: false,
    margin: 0,
    loop: true,
    autoplay: true,
    responsive: {
        0: {
            items: 1
        },
        480: {
            items: 1
        },
        768: {
            items: 1
        }
    }
});



$(document).ready(function () {


    $('.desktop_screen .shop-box-icon-carousel').owlCarousel({
        nav: true,
        navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
        margin: 10,
        loop: false,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 3
            },
            480: {
                items: 3
            },
            768: {
                items: 4
            },
            1000: {
                items: 6
            }
        }
    });

    $('#custCarousel').owlCarousel({
        nav: true,
        navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
        margin: 10,
        loop: false,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 3
            },
            480: {
                items: 3
            },
            768: {
                items: 4
            },
            1000: {
                items: 6
            }
        }
    });


});

$(document).ready(function () {


    $('.mobile_screen .shop-box-icon-carousel').owlCarousel({
        nav: true,
        navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"  id="show-more"/>'],
        margin: 10,
        loop: false,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 3
            },
            480: {
                items: 3
            },
            768: {
                items: 4
            },
            1000: {
                items: 6
            }
        }
    });
});

$(document).ready(function () {
    $('#show-more').on('click', function () {
        $('#initial-items').addClass('hidden');
        $('#all-items').removeClass('hidden');
        $('#all-items').owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow" />'],
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








$(document).ready(function () {
    $('.product-logo-icon-carousel').owlCarousel({
        nav: true,
        margin: 10,
        navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
        loop: false,
        autoplay: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 4
            },
            375: {
                items: 4
            },
            480: {
                items: 4
            },
            768: {
                items: 2
            },
            1000: {
                items: 6
            }
        }
    });
});

$(document).ready(function () {
    $('.product-carousel').owlCarousel({
        nav: true,
        margin: 10,
        navText: [
            '<img class="img-fluid" src="https://jeweljagat.com/front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>',
            '<img class="img-fluid" src="https://jeweljagat.com/front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'
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
            768: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });
});



window.onscroll = function () { myFunction() };

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
    nav: true,
    margin: 0,
    navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
    loop: false,
    autoplay: true,
    responsive: {
        0: {
            items: 1
        },
        480: {
            items: 1
        },
        768: {
            items: 1
        }
    }
});



// Show or hide the back-to-top button
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $('#back-top').fadeIn();
    } else {
        $('#back-top').fadeOut();
    }
});

// Animate the scroll to top
$('#back-top').click(function () {
    $('html, body').animate({ scrollTop: 0 }, 800);
    return false;
});


$(document).ready(function () {

    $('#specialize-tab li:first-child').addClass('active_li');
    $("#specialize-tab li").click(function () {

        $("#specialize-tab li.active_li").removeClass("active_li");
        $(this).addClass("active_li")
    })

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

function showToast() {
    var toast = document.getElementById("success_toast");
    toast.className = "toast show";
    setTimeout(function () { toast.className = toast.className.replace("show", ""); }, 3000);
}

function showCartToast() {
    var toast = document.getElementById("success_toast_cart");
    toast.className = "toast show";
    setTimeout(function () { toast.className = toast.className.replace("show", ""); }, 3000);
}


function increaseQty(qtyId) {
    var location = document.getElementById(qtyId);
    var currentQty = location.value;
    var qty = Number(currentQty) + 1;
    location.value = qty;
}

function decreaseQty(qtyId) {
    var location = document.getElementById(qtyId);
    var currentQty = location.value;
    if (currentQty > 1) {
        var qty = Number(currentQty) - 1;
        location.value = qty;
    }
}


// Added By Niharika 30-05-2024
function fun_scribe_email() {

    var email = $('#text_subscribe_email').val();

    var flag = 0;
    if (email == '' || email == null) {
        flag++;
        Swal.fire({
            title: "Oops...",
            text: "Invalid your email Address!",
            icon: "error",
            buttonsStyling: false,
            confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
        });
    }

    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (email.match(mailformat)) {
        $("#div_email").removeClass('has-error');
        $("#msg_email").html("");
    } else {
        flag++;
        Swal.fire({
            title: "Oops...",
            text: "Invalid your email Address!",
            icon: "error",
            buttonsStyling: false,
            confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
        });
    }

    if (flag == 0) {

        $.ajax({
            url: subscribeemail,
            data: {
                'email': email,
                '_token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: 'POST',
            success: function (result) {
                if (result.success == 'TRUE') {
                    Swal.fire({
                        title: "Success",
                        text: "Successfully Subscribe email",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                    $('#text_subscribe_email').val('');
                } else {
                    Swal.fire({
                        title: "Oops...",
                        text: result.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                }
            }
        });
    }
}
function share_model(id, type) {

    var token = $("meta[name='csrf-token']").attr("content");
    var id = id;

    $.ajax({
        url: sharemodel,
        type: 'POST',
        data: {
            _token: token,
            id: id,
            type: type,
        },
        success: function (response) {
            $('#share_popup').modal('show');
            $("#product_images").attr("src", response.image_path);
            $("#product_name").text(response.name);
            $('#share_type').html(response.type);
            if (type == 'product') {
                var slug_type = 'products/';
            }
            if (type == 'catalogs') {
                var slug_type = 'catalogue/';
            }
            if (type == 'collection') {
                var slug_type = 'collection/';
            }
            if (type == 'product-page') {
                var slug_type = 'products';
            }
            if (type == 'current-url') {
                var current_url = $(location).prop('href');
                var w_url = 'https://api.whatsapp.com/send?text=' + current_url;
                $('.whatsapp_share').attr('onclick', "window.open('" + w_url + "', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var f_url = 'https://www.facebook.com/sharer/sharer.php?u=' + current_url;
                $('.facebook_share').attr('onclick', "window.open('" + f_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var t_url = 'https://twitter.com/intent/tweet?url=' + current_url;
                $('.twitter_share').attr('onclick', "window.open('" + t_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var i_url = 'https://www.instagram.com/';
                $('.insta_share').attr('onclick', "window.open('" + i_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var c_url = current_url;
                $('.copy_link_share').attr('onclick', "copyLink('" + c_url + "')");
            } else if (type == 'product-page') {
                var w_url = 'https://api.whatsapp.com/send?text=' + BASE_URL + '' + slug_type;
                $('.whatsapp_share').attr('onclick', "window.open('" + w_url + "', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var f_url = 'https://www.facebook.com/sharer/sharer.php?u=' + BASE_URL + '' + slug_type;
                $('.facebook_share').attr('onclick', "window.open('" + f_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var t_url = 'https://twitter.com/intent/tweet?url=' + BASE_URL + '' + slug_type;
                $('.twitter_share').attr('onclick', "window.open('" + t_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var i_url = 'https://www.instagram.com/';
                $('.insta_share').attr('onclick', "window.open('" + i_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var c_url = BASE_URL + slug_type;
                $('.copy_link_share').attr('onclick', "copyLink('" + c_url + "')");
            } else {
                var w_url = 'https://api.whatsapp.com/send?text=' + BASE_URL + '' + slug_type + '' + response.slug;
                $('.whatsapp_share').attr('onclick', "window.open('" + w_url + "', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var f_url = 'https://www.facebook.com/sharer/sharer.php?u=' + BASE_URL + '' + slug_type + '' + response.slug;
                $('.facebook_share').attr('onclick', "window.open('" + f_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var t_url = 'https://twitter.com/intent/tweet?url=' + BASE_URL + '' + slug_type + '' + response.slug;
                $('.twitter_share').attr('onclick', "window.open('" + t_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var i_url = 'https://www.instagram.com/';
                $('.insta_share').attr('onclick', "window.open('" + i_url + "','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400')");
                var c_url = BASE_URL + slug_type + response.slug;
                $('.copy_link_share').attr('onclick', "copyLink('" + c_url + "')");
            }
        },
    });
}
function copyLink(value) {
    const el = document.createElement('input');
    el.value = value;
    document.getElementById('copy_share').appendChild(el);
    el.select();
    document.execCommand('copy');
    document.getElementById('copy_share').removeChild(el);

    Swal.fire({
        icon: 'success',
        title: 'Link copied successfully.',
        text: ' ',
        buttons: false,
        timer: 1500
    });
    return false;
}

function addToWishlist(bpr_id, product_title, slug, product_image, price) {

    var token = $("meta[name='csrf-token']").attr("content");
    var id = bpr_id;
    var name = product_title;
    var slug = slug;
    var image = product_image;
    var price = price;
    $.ajax({
        url: addwishlst,
        type: 'POST',
        data: {
            'id': id,
            'name': name,
            'slug': slug,
            'image': image,
            'price': price,
            '_token': token
        },
        success: function (responseOBJ) {
            if (responseOBJ.status == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Product Successfully Added to Wishlist.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });

                $("#whishlist_count").html('(' + responseOBJ.count + ')');
                $("#whishlist_count_mob").html('(' + responseOBJ.count + ')');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error In Adding Product to Wishlist.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });
            }
        }
    });
}

function addToCart(pro_id, product_title, price, index, buy_now) {

    var qty = $('#product_qty').val();
    if (qty != '' && qty != null) {
        qty = qty;
    } else {
        qty = 1;
    }
    var pro_id = pro_id;
    var pr_bpr_id = pro_id;
    var bpr_pr_title = product_title;
    var bpr_price = price;
    $.ajax({
        url: addtocrt,
        type: 'POST',
        data: {
            'pro_id': pro_id,
            'qty': qty,
            '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (responseOBJ) {
            if (responseOBJ.status == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Product Successfully Added to Cart.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });
                $("#cart_count").html('(' + responseOBJ.count + ')');
                $("#cart_count_mob").html('(' + responseOBJ.count + ')');
                if (buy_now && buy_now == 'buynow') {
                    window.location.href = front_cart;
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error In Adding Product to Cart.',
                    text: ' ',
                    buttons: false,
                    position: 'top',
                    timer: 1000
                });
            }
        }
    });

}

$(document).ready(function () {
    var category_id = $('.category_id_first').val();
    if (category_id) {
        product_details(category_id);
    }
    append_blade();
});

function product_details(val) {
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: homecatdet,
        type: 'POST',
        data: {
            _token: token,
            id: val,
        },
        success: function (response) {
            $('#goldjewelry').html('');
            $('#goldjewelry').append(response);
        },
    });
}

function append_blade() {
    var token = $("meta[name='csrf-token']").attr("content");

    // $.ajax({
    //     url: getpopups,
    //     type: 'POST',
    //     data: {
    //     _token: token,
    //     },
    //     success: function (response) {
    //         $('#Append_poups').html('');
    //         $('#Append_poups').append(response);
    //     },
    // });
}

$('.view-more-header').click(function (e) {
    e.preventDefault();
    $('.each-specialize').removeClass('d-none');
    $('.view-more-header').addClass('d-none');
});

$(document).on("click", ".view-more-link", function (e) {
    e.preventDefault();
    $('.product-extra-item').removeClass('d-none');
    $('.view-more-sec').addClass('d-none');
    $('.show-less').removeClass('d-none');
    $('.all-product-link-sec').removeClass('d-none');
});

$(document).on("keyup", "#autocomplete, #autocomplete_mobile", function () {
    var search_text = $(this).val();
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: serchprdct,
        type: 'POST',
        data: {
            _token: token,
            search_text: search_text,
        },
        success: function (response) {
            $('#results').html('');
            $("#desktop_search_cross, #mobile_search_cross").show();
            if (response.status == 1) {
                $('#results, #results_mobile').append(response.html);
            } else {
                $('#results, #results_mobile').html('');
            }
        }
    });
});

$(document).on("click", "#desktop_search_cross, #mobile_search_cross", (function () { $("#autocomplete, #autocomplete_mobile").val(""), $(this).hide(), $("#results, #results_mobile").html("") }));

function checkValueStringOrNumber(type) {
    var value = "";
    if (type == "email") {
        value = $("#login-email").val();
        $("#login_mobile").val(value);
    } else if (type == "mobile") {
        value = $("#login_mobile").val();
        $("#login-email").val(value);
    }
    if (isNaN(value)) {
        $('#login_email_div').removeClass('d-none');
        $('#login_mobile_div').addClass('d-none');
        $("#login-email").focus();
    } else {
        $('#login_mobile_div').removeClass('d-none');
        $('#login_email_div').addClass('d-none');
        $('#msg_email').addClass('d-none');
        $('.msg_email_for_login').addClass('d-none');
        $('.msg_email_for_login').removeClass('d-block');
        $("#login_mobile").focus();
    }
}

$('#hide_password_reg').click(function () {
    $('#show_password_reg').removeClass('d-none');
    $('#registration_password').attr("type", "text");
    $('#hide_password_reg').addClass('d-none');
});
$('#show_password_reg').click(function () {
    $('#hide_password_reg').removeClass('d-none');
    $('#registration_password').attr("type", "password");
    $('#show_password_reg').addClass('d-none');
});
$('#hide_password').click(function () {
    $('#show_password').removeClass('d-none');
    $('.dynamic_show_password').attr("type", "text");
    $('#hide_password').addClass('d-none');
});
$('#show_password').click(function () {
    $('#hide_password').removeClass('d-none');
    $('.dynamic_show_password').attr("type", "password");
    $('#show_password').addClass('d-none');
});
$('#hide_password_login').click(function () {
    $('#show_password_login').removeClass('d-none');
    $('#login_password').attr("type", "text");
    $('#hide_password_login').addClass('d-none');
});
$('#show_password_login').click(function () {
    $('#hide_password_login').removeClass('d-none');
    $('#login_password').attr("type", "password");
    $('#show_password_login').addClass('d-none');
});
$('#hide_new_forgot_password').click(function () {
    $('#show_new_forgot_password').removeClass('d-none');
    $('#new_update_password').attr("type", "text");
    $('#hide_new_forgot_password').addClass('d-none');
});
$('#show_new_forgot_password').click(function () {
    $('#hide_new_forgot_password').removeClass('d-none');
    $('#new_update_password').attr("type", "password");
    $('#show_new_forgot_password').addClass('d-none');
});
$('#hide_confirm_forgot_password').click(function () {
    $('#show_confirm_forgot_password').removeClass('d-none');
    $('#update_confirm_password').attr("type", "text");
    $('#hide_confirm_forgot_password').addClass('d-none');
});
$('#show_confirm_forgot_password').click(function () {
    $('#hide_confirm_forgot_password').removeClass('d-none');
    $('#update_confirm_password').attr("type", "password");
    $('#show_confirm_forgot_password').addClass('d-none');
});


function checkForgetPasswordValueStringOrNumber(type) {
    var value = "";
    if (type == "email") {
        value = $("#email2").val();
    }
    /*   console.log(event);
     var value = $(".login_email_mobile").val();*/
    if (isNaN(value)) {
        $('#forgot_password_email_div').removeClass('d-none');
        $("#email2").focus();
    } else {
        $('#forgot_password_email_div').removeClass('d-none');
        $("#email2").focus();
    }
}

function loginCallback() {
    $("#msg_rec_name1").html("");
    $("#msg_rec_name_checked").val('1');
};

function userLogin() {
    $("#msg_rec_name1").html("");

    var email_val = $("#login-email").val();
    var mobile_val = $("#login_mobile").val();
    var password_val = $('#login_password').val();
    var role = $('#user_role').val();
    var checked = $('#msg_rec_name_checked').val();
    var mobile_no_regx = /^[0-9]{4,10}$/;
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var flag = 0;
    if (!checked) {
        flag++;
        $("#msg_rec_name1").html("Please check the recaptcha");
    }
    if (mobile_val == '') {
        $("#msg_mobile4").html("Mobile number Or Email Address is required");
        $("#msg_mobile4").css("display", "block");
        flag++;
    } else {
        $("#msg_mobile4").html("");
        $("#msg_mobile4").css("display", "none");
    }
    if (email_val == '') {
        $("#msg_email").html("Mobile number Or Email Address is required");
        $("#msg_email").css("display", "block");
        flag++;
    } else {
        $("#msg_email").html("");
        $("#msg_email").css("display", "none");
    }
    if (password_val == '') {
        $("#msg_login_password").html("Password is required");
        $("#msg_login_password").css("display", "block");
        flag++;
    } else {
        $("#msg_login_password").html("");
        $("#msg_login_password").css("display", "none");
    }

    if (flag == 0) {
        if (mobile_val.match(mobile_no_regx)) {
            // alert('mobile');
            $.ajax({
                url: loginRoute,
                type: 'POST',
                data: {
                    'phone': mobile_val,
                    'password': password_val,
                    'role': role,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    if (response.status == 1) {
                        window.location.reload();
                    } else {
                        $('#login_alert').removeClass('d-none');
                        $("#login_alert_msg").html(response.message);
                        $("#login_alert_msg").css("display", "block");
                    }
                }
            });
        } else if (email_val.match(email_regx)) {
            // alert('email');
            $.ajax({
                url: loginRoute,
                type: 'POST',
                data: {
                    'email': email_val,
                    'password': password_val,
                    'role': role,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    if (response.status == 1) {
                        window.location.reload();
                    } else {
                        $('#login_alert').removeClass('d-none');
                        $("#login_alert_msg").html(response.message);
                        $("#login_alert_msg").css("display", "block");
                    }
                }
            });
        } else {
            $('#user_login1').prop("disabled", true);
            return false;
        }
    }
}
$("#login-email").keyup(function (event) {
    if (event.which == 13) {
        userLogin()
    }
});
$("#login_mobile").keyup(function (event) {
    if (event.which == 13) {
        userLogin()
    }
});
$("#login_password").keyup(function (event) {
    if (event.which == 13) {
        userLogin()
    }
});

$('input[type=checkbox][name=terms]').change(function () {
    if ($('input[name="terms"]:checked').val() == 'true') {
        $('#register_btn').prop("disabled", false);
        $('#register_btn').CSS("cursor", "pointer");
    } else {
        $('#register_btn').prop("disabled", true);
        $('#register_btn').CSS("cursor", "not-allowed");
    }
});


function showForgotPasswordModal() {
    $('#forgotPasswordModal').modal('show');
    $('#login-popup').modal('hide');
}

function showUpdatePasswordModal() {
    $('#changePasswordModal').modal('show');
    $('#forgotPasswordModal').modal('hide');
    $('#login-popup').modal('hide');
}

function updatePassword() {
    var email = '';
    var mobile = '';
    var tempPassword = $('#temporary_access_code').val();
    var new_password = $('#new_update_password').val();
    var confirm_password = $('#update_confirm_password').val();
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var flag = 0;
    if (tempPassword == '') {
        $("#msg_temp_access_code").html("Temporary Access Code is required");
        $("#msg_temp_access_code").css("display", "block");
        flag++;
    } else {
        $("#msg_temp_access_code").html("");
        $("#msg_temp_access_code").css("display", "none");
    }
    if (new_password == '') {
        $("#msg_new_update_password").html("New Password required");
        $("#msg_new_update_password").css("display", "block");
        flag++;
    } else if (new_password != '' && new_password.length < 6) {
        $("#msg_new_update_password").html(
            "Please recheck, your Password should contain minimum 6 characters. It's a required Field.");
        $("#msg_new_update_password").css("display", "block");
        flag++;
    } else {
        $("#msg_new_update_password").html("");
        $("#msg_new_update_password").css("display", "none");
    }
    if (confirm_password == '') {
        $("#msg_update_confirm_password").html("Confirm Password required");
        $("#msg_update_confirm_password").css("display", "block");
        flag++;
    } else if (new_password != confirm_password) {
        $("#msg_update_confirm_password").html("New Password and Confirm Password doesn't match");
        $("#msg_update_confirm_password").css("display", "block");
        flag++;
    } else {
        $("#msg_update_confirm_password").html("");
        $("#msg_update_confirm_password").css("display", "none");
    }
    if ($('#email2').val().match(email_regx)) {
        email = $('#email2').val();
    }
    if (flag == 0) {
        $.ajax({
            url: updatepass,
            type: 'POST',
            data: {
                'email': email,
                'temporary_access_code': tempPassword,
                'new_password': new_password,
                '_token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                // var obj = jQuery.parseJSON(response);
                if (response.status == 200) {
                    $('#loader').hide();
                    Swal.fire({
                        title: "Success",
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                    $('#changePasswordModal').modal('hide');
                    $('#login-popup').modal('show');
                    $('#update_password_alert').addClass('d-none');
                    $('#update_password_alert_msg').html('');
                    $('#update_password_alert_msg').addClass('d-none');
                } else {
                    /*swal("Error", obj.MESSAGE, "error");*/
                    $('#update_password_alert').removeClass('d-none');
                    $('#update_password_alert_msg').html(response.message);
                    $('#update_password_alert_msg').addClass('d-block');
                }
            }
        });
    }
}

function forgetPassword() {
    var email = $('#email2').val();
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var flag = 0;
    var scroll_element = '';
    if (email == '') {
        $("#msg_email2").html("Email Address is required");
        $("#msg_email2").css("display", "block");
        flag++;
    } else if (!email.match(email_regx)) {
        $("#msg_email2").html("Enter Valid Email Address");
        $("#msg_email2").css("display", "block");
        flag++;
    } else {
        $("#msg_email2").html("");
        $("#msg_email2").css("display", "none");
    }
    if (flag == 0) {
        if (email.match(email_regx)) {
            $.ajax({
                url: forgotpass,
                type: 'POST',
                data: {
                    'email': email,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {

                    // var obj = jQuery.parseJSON(response);
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                        });
                        setTimeout(function () {
                            // window.location.reload();
                        }, 3000);
                        $("#message_update_password").html("Check your Email for Temporary Code");
                        $("#message_update_password").css("display", "block");
                        showUpdatePasswordModal();
                    } else {
                        // swal("Invalid Email Address", "We are unable to identify you with your e-mail.",
                        // "warning");
                        Swal.fire({
                            title: "Invalid Email Address",
                            text: "We are unable to identify you with your e-mail.",
                            icon: "warning",
                            buttonsStyling: false,
                            confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                        });
                    }
                }
            });
        } else {
            return false;
        }

    } else {
        return false;
    }
}


function registerCallback() {
    $("#msg_rec_name").html("");
    $("#rec_name_checked").val('1');
};


function saveData() {

    var si_name = $('#reg_name').val();
    var si_mobile = $('#reg_mobile').val();
    var si_email = $('#reg_email').val();
    var si_registration_password = $('#registration_password').val();
    var si_c_password = $('#confirm_password').val();
    var otp = $('#reg_otp').val();
    var formData = new FormData($('#registration_form')[0]);
    var flag = 0;
    //var name_regx = ;
    /* Name Validation*/
    $("#msg_rec_name").html("");

    var checked = $('#rec_name_checked').val();

    if (!checked) {
        flag++;
        $("#msg_rec_name").html("Please check the recaptcha");
    }

    if (si_name == '' || si_name == null) {
        flag++;
        $("#div_name").addClass('has-error');
        $("#msg_reg_name").html("Name is required.");
    } else {
        $("#div_name").removeClass('has-error');
        $("#msg_reg_name").html("");
    }
    if (si_name != '' && si_name.match(/^[a-zA-Z ]+$/)) {
        $("#div_name").removeClass('has-error');
        $("#msg_reg_name").html("");
    } else {
        $("#div_name").addClass('has-error');
        $("#msg_reg_name").html("Name must be in alphabets and spaces only.");
        flag++;
    }
    if (si_registration_password == '' || si_registration_password == null) {
        flag++;
        $('#msg_password').html('Password is Required')
    } else {
        $("#div_name").removeClass('has-error');
        $("#msg_name").html("");
    }

    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;

    if (si_registration_password.length < 8) {
        $('#msg_password').html("");
        $('#msg_password').html("Password Length must be atleast 8 characters.");
        flag++;
    } else {
        if (si_registration_password.match(number) && si_registration_password.match(alphabets) && si_registration_password.match(special_characters)) {
            $('#msg_password').html("");
            if (si_registration_password != si_c_password) {
                $('#cmsg_password').html("");
                $('#cmsg_password').html("Password does not match.");
                flag++;
            }
        }
        else {
            flag++;
            $('#msg_password').html("");
            $('#msg_password').html("Password should include alphabets, numbers and special characters.");
        }

    }

    /*
        Mobile Validation.
        */
    var mobile_regx = /^\d{10}$/;
    if (si_mobile == '' || si_mobile == null) {
        flag++;
        $("#div_mobile").addClass('has-error');
        $("#msg_mobile").html("Mobile number is required");
    } else if ((si_mobile != '') && !(si_mobile.match(mobile_regx))) {
        $("#div_mobile").addClass('has-error');
        $("#msg_mobile").html("Invalid Mobile Number.");
        flag++;
    } else {
        $("#div_mobile").removeClass('has-error');
        $("#msg_mobile").html("");
    }

    /*
        email validation.
    */
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (si_email == '' || si_email == null) {
        flag++;
        $("#div_email1").addClass('has-error');
        $("#msg_email_reg").html("Email is required.");
    } else if ((si_email != '') && (!(si_email.match(mailformat)))) {
        $("#div_email1").addClass('has-error');
        $("#msg_email_reg").html("Invalid Email.");
        flag++;
    } else {
        $("#div_email1").removeClass('has-error');
        $("#msg_email_reg").html("");
    }
    if (otp == '' || otp == null) {
        flag++;
        $("#msg_reg_otp").html("OTP is required.");
    } else if (otp.length < 6 || otp.length > 6) {
        flag++;
        $("#msg_reg_otp").html("OTP must be 6 digit.");
    } else if ((otp != '') && !(otp.match(/^[0-9]+$/))) {
        flag++;
        $("#msg_reg_otp").html("OTP must be numeric.");
    } else {
        $("#msg_reg_otp").html("");
    }

    if (flag == 0) {
        /* $('#registration_form').submit(); */
        $('#register_btn').prop('disabled', true);
        $('#loader').show(); // Show loading indicator
        $.ajax({
            url: registeruser,
            type: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {

                // console.log(response);

                // var obj = jQuery.parseJSON(response);
                if (response.status == 200) {
                    $('#loader').hide();
                    $('#register_btn').prop('disabled', true);
                    // swal("Thank You", response.message, "success");
                    Swal.fire({
                        title: "Thank You",
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                    setTimeout(
                        function () {
                            var redirectUrl = BASE_URL;
                            window.location.href = redirectUrl;
                        },
                        500);
                } else if (response.status == 402) {
                    $('#loader').hide();
                    $('#register_btn').prop('disabled', false);
                    Swal.fire({
                        title: "Info",
                        text: response.message,
                        icon: "info",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                    //  var redirectUrl =
                    //     "https://jewelxy.workdemo.in.net/";
                    // window.location.href = redirectUrl; 
                } else {
                    $('#loader').hide();
                    $('#register_btn').prop('disabled', false);
                    $('#register_btn').css('cursor', 'pointer');
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonColor: "var(--theme-orange-red)", // Set your desired color here
                    });
                }
            }
        });
    } else {
        return false;
    }
    //      });
    //  });
}


function sendRegOtp() {
    var email = $('#reg_email').val();
    var name = $('#reg_name').val();
    var flag = 0;
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (email == '' || email == null) {
        flag++;
        $("#msg_email_reg").addClass('has-error');
        $("#msg_email_reg").html("Email is required.");
    } else if ((email != '') && (!(email.match(mailformat)))) {
        $("#msg_email_reg").addClass('has-error');
        $("#msg_email_reg").html("Invalid Email.");
        flag++;
    } else {
        $("#msg_email_reg").removeClass('has-error');
        $("#msg_email_reg").html("");
    }
    $('#reg_send_email_otp, #reg_resend_email_otp').off('click');
    if (flag == 0) {
        $('#reg_send_email_otp .spinner_btn').show();
        $.ajax({
            url: sendregcd,
            type: 'POST',
            data: {
                'name': name,
                'email': email,
            },
            success: function (response) {
                // var obj = jQuery.parseJSON(response);
                if (response.status == 200) {
                    $('#reg_send_email_otp .spinner_btn').hide();
                    $('#reg_send_email_otp').prop('disabled', true);
                    $('#reg_send_email_otp').addClass('disableClick');
                    $('#reg_resend_email_otp').addClass('disableClick');
                    $("#msg_email_reg").addClass('has-error');
                    $("#msg_email_reg").html(response.message);
                    $('#reg_send_expired_otp').removeClass('d-none');
                    clearInterval(interval);
                    $('#reg_login_timer').text("2:30");
                    regCountdown();
                } else {
                    $('#reg_send_email_otp .spinner_btn').hide();
                    $('#reg_send_email_otp').prop('disabled', false);
                    $("#msg_email_reg").addClass('has-error');
                    $("#msg_email_reg").html(response.message);
                    $('#reg_send_email_otp, #reg_resend_email_otp').on('click');
                }
            }
        });
    }
}

function ResendRegOtp() {
    sendRegOtp();
}

var interval;

function countdown() {
    clearInterval(interval);
    interval = setInterval(function () {
        var login_timer = $('#login_timer').html();
        login_timer = login_timer.split(':');
        var minutes = login_timer[0];
        var seconds = login_timer[1];
        seconds -= 1;
        if (minutes < 0) return;
        else if (seconds < 0 && minutes != 0) {
            minutes -= 1;
            seconds = 59;
        } else if (seconds < 10 && length.seconds != 2) seconds = '0' + seconds;

        $('#login_timer').html(minutes + ':' + seconds);

        if (minutes == 0 && seconds == 0) {
            clearInterval(interval);
            $('#send_expired_otp').addClass('d-none');
            $('#reg_send_email_otp').addClass('d-none');
            $('#login_timer').html("00:00");
            $('#resend_email_otp').removeClass('d-none');
            $('#reg_resend_email_otp').removeClass('d-none');
            $('#send_email_otp').addClass('d-none');
            $('#resend_email_otp').prop('disabled', false);
            $('#reg_resend_email_otp').prop('disabled', false);
        }
    }, 1000);
}

function regCountdown() {
    clearInterval(interval);
    $('#reg_send_email_otp, #reg_resend_email_otp').off('click');
    interval = setInterval(function () {
        $('#reg_send_email_otp, #reg_resend_email_otp').off('click');
        var login_timer = $('#reg_login_timer').html();
        login_timer = login_timer.split(':');
        var minutes = login_timer[0];
        var seconds = login_timer[1];
        seconds -= 1;
        if (minutes < 0) return;
        else if (seconds < 0 && minutes != 0) {
            minutes -= 1;
            seconds = 59;
        } else if (seconds < 10 && length.seconds != 2) seconds = '0' + seconds;

        $('#reg_login_timer').html(minutes + ':' + seconds);

        if (minutes == 0 && seconds == 0) {
            clearInterval(interval);
            $('#reg_send_email_otp').addClass('d-none');
            $('#reg_login_timer').html("00:00");
            $('#reg_resend_email_otp').removeClass('disableClick');
            $('#reg_send_email_otp').removeClass('disableClick');
            $('#reg_send_email_otp, #reg_resend_email_otp').on('click');
            $('#reg_resend_email_otp').removeClass('d-none');
            $('#reg_resend_email_otp').prop('disabled', false);
        }

    }, 1000);
}

function activaTab(tab) {
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};

function checkForgetPasswordValueStringOrNumber(type) {
    var value = "";
    if (type == "email") {
        value = $("#email2").val();
    }
    /*   console.log(event);
     var value = $(".login_email_mobile").val();*/
    if (isNaN(value)) {
        $('#forgot_password_email_div').removeClass('d-none');
        $("#email2").focus();
    } else {
        $('#forgot_password_email_div').removeClass('d-none');
        $("#email2").focus();
    }
}



var width = $('body').width();

if (width < 421) {
    var scale = ((width / 421) - 0.1);
    $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
    $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
    $('.g-recaptcha').css('transform-origin', '0 0');
    $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
}
