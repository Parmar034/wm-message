document.addEventListener("DOMContentLoaded", (function () {
  const e = document.querySelector(".cookies-section"),
    s = document.querySelector(".cookies-btn");
  e && s && ("true" === localStorage.getItem("cookiesConsent") && e.classList.add("hide"), s.addEventListener("click", (function () {
    e.classList.add("hide"), localStorage.setItem("cookiesConsent", "true")
  })));
  const i = document.querySelector(".header-top-section"),
    t = document.querySelector(".close-welcome-text");
  i && t && ("true" === localStorage.getItem("headerClosed") && i.classList.add("hide"), t.addEventListener("click", (function () {
    i.classList.add("hide"), closeNotification(), localStorage.setItem("headerClosed", "true")
  })))
})), $(document).ready((function () {
  $(".pdf-logo-icon").owlCarousel({
    nav: !0,
    margin: 10,
    navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
    loop: !0,
    autoplay: !0,
    responsiveClass: !0,
    responsive: {
      0: {
        items: 2
      },
      600: {
        items: 4
      },
      1e3: {
        items: 6
      }
    }
  })
})), $(".banner-carousel, .testimonial-carousel").owlCarousel({
  nav: !1,
  navText: !1,
  margin: 0,
  loop: !0,
  autoplay: !0,
  autoplayTimeout: 7000,
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
}), $(document).ready((function () {
  $(".desktop_screen .shop-box-icon-carousel").owlCarousel({
    nav: !0,
    navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
    margin: 10,
    loop: !0,
    autoplay: !0,
    responsiveClass: !0,
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
      1e3: {
        items: 6
      }
    }
  })
})), $(document).ready((function () {
  $(".mobile_screen .shop-box-icon-carousel").owlCarousel({
    nav: !0,
    navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"  id="show-more"/>'],
    margin: 0,
    loop: !0,
    autoplay: !0,
    responsiveClass: !0,
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
      1e3: {
        items: 6
      }
    }
  })
})), $(document).ready((function () {
  $("#show-more").on("click", (function () {
    $("#initial-items").addClass("hidden"), $("#all-items").removeClass("hidden"), $("#all-items").owlCarousel({
      loop: !0,
      margin: 0,
      nav: !0,
      navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow" />'],
      lazyLoad: !0,
      responsive: {
        0: {
          items: 3
        },
        600: {
          items: 5
        },
        1e3: {
          items: 8
        }
      }
    })
  }))
})), $(document).ready((function () {
  $(".product-logo-icon-carousel").owlCarousel({
    nav: !0,
    margin: 10,
    navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
    loop: !0,
    autoplay: !1,
    responsiveClass: !0,
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
      1e3: {
        items: 8
      }
    }
  })
})), $(document).ready((function () {
  $(".product-carousel").owlCarousel({
    nav: !0,
    margin: 10,
    navText: ['<img class="img-fluid" src="https://jeweljagat.com/front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="https://jeweljagat.com/front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
    loop: !0,
    autoplay: !0,
    responsiveClass: !0,
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
      1e3: {
        items: 4
      }
    }
  })
})), window.onscroll = function () {
  myFunction()
};
var header = document.getElementById("myHeader"),
  sticky = header.offsetTop;

function myFunction() {
  window.pageYOffset > sticky ? header.classList.add("sticky") : header.classList.remove("sticky")
}

function openForm(e, s) {
  var i, t, o;
  for (t = document.querySelectorAll(".tabcontent, .tabcontent_popup"), i = 0; i < t.length; i++) t[i].style.display = "none";
  for (o = document.querySelectorAll(".tablinks, .tablinks_popup"), i = 0; i < o.length; i++) o[i].classList.remove("active");
  document.getElementById(s).style.display = "block", e.currentTarget.classList.add("active")
}
$(".testimonial-carousel").owlCarousel({
  nav: !0,
  margin: 0,
  navText: ['<img class="img-fluid" src="./front/src/images/slider-left-arrow.svg" width="8" height="12" alt="arrow"/>', '<img class="img-fluid" src="./front/src/images/slider-right-arrow.svg" width="8" height="12" alt="arrow"/>'],
  loop: !0,
  autoplay: !0,
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
}), $(window).scroll((function () {
  $(this).scrollTop() > 100 ? $("#back-top").fadeIn() : $("#back-top").fadeOut()
})), $("#back-top").click((function () {
  return $("html, body").animate({
    scrollTop: 0
  }, 800), !1
})), $(document).ready((function () {
  $("#specialize-tab li:first-child").addClass("active_li"), $("#specialize-tab li").click((function () {
    $("#specialize-tab li.active_li").removeClass("active_li"), $(this).addClass("active_li")
  }))
}));

if (!localStorage.getItem("headerClosed")) {
  openNotification();
} else {
  closeNotification();
}

$('.close-welcome-text').on('click', function () {
  closeNotification();
});

function openNotification() {
  $('.header-top-section').removeClass('d-none');
  if (display_pg == 1) {
    $('.banner-slider-section').addClass('header-note');
    $('.margin-top-head').addClass('header-note');
  }

}

function closeNotification() {
  $('.header-top-section').addClass('d-none');
  $('.margin-top-head').removeClass('header-note');
  $('.banner-slider-section').removeClass('header-note');
  $('.margin-top-head').removeClass('header-note');
}
var display_status = $('#daily_status_display').val();

if (display_status == 'home') {
  if ($(location).prop('href') == BASE_URL) {
    if (!localStorage.getItem("popupShown")) {
      setTimeout(function () {
        openPopupModel();
      }, 3000);
    }
  }
} else {
  if (!localStorage.getItem("popupShown")) {

    setTimeout(function () {
      openPopupModel();
    }, 3000);
  }
}

function openPopupModel() {
  $("#myPopupModelOverlay").show();
}

function closePopupModel() {
  $("#myPopupModelOverlay").hide();
  localStorage.setItem("popupShown", "true");
}
