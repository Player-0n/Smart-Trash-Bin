(function() {
    
    "use strict";
    
    //===== Prealoder

    window.onload = function() {
        window.setTimeout(fadeout, 500);
    }

    function fadeout() {
        document.querySelector('.preloader').style.opacity = '0';
        document.querySelector('.preloader').style.display = 'none';
    }

    function toggleScrolled() {
        const selectBody = document.querySelector('body');
        const selectHeader = document.querySelector('#header');
        if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
        window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
      }

      window.addEventListener('scroll', function () {
        const header = document.getElementById('header_navbar');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    /*=====================================
    Sticky
    ======================================= */
    /*window.onscroll = function () {
        var header_navbar = document.getElementById("header_navbar");
        var logo = document.querySelector("img#logo");
        var backToTo = document.querySelector(".back-to-top");
    
        if (window.scrollY > 50) { 
            header_navbar.classList.add("sticky");
            logo.setAttribute("src", "/frontend/assets/landing-assets/images/stb.png"); // Sticky logo
        } else {
            header_navbar.classList.remove("sticky");
            logo.setAttribute("src", "/frontend/assets/landing-assets/images/logo-2.svg"); // Default logo
        }
    
        backToTo.style.display = window.scrollY > 50 ? "block" : "none";
    };*/

    // Get the navbar


    // for menu scroll 
    var pageLink = document.querySelectorAll('.page-scroll');
    
    pageLink.forEach(elem => {
        elem.addEventListener('click', e => {
            e.preventDefault();
            document.querySelector(elem.getAttribute('href')).scrollIntoView({
                behavior: 'smooth',
                offsetTop: 1 - 60,
            });
        });
    });

    // section menu active
    function onScroll(event) {
        var sections = document.querySelectorAll('.page-scroll');
        var scrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;

        for (var i = 0; i < sections.length; i++) {
            var currLink = sections[i];
            var val = currLink.getAttribute('href');
        
            // Ensure val is a valid selector and the target section exists
            if (!val || val === "#" || val === "") continue; // Skip invalid href values
        
            var refElement = document.querySelector(val);
        
            if (refElement) { // Check if refElement is not null before using offsetTop
                var scrollTopMinus = scrollPos + 73;
                if (refElement.offsetTop <= scrollTopMinus && 
                    (refElement.offsetTop + refElement.offsetHeight > scrollTopMinus)) {
                    
                    // Remove 'active' class from all .page-scroll elements
                    document.querySelectorAll('.page-scroll').forEach(el => el.classList.remove('active'));
        
                    // Add 'active' class to the current link
                    currLink.classList.add('active');
                } else {
                    currLink.classList.remove('active');
                }
            }
        }
    };

    window.document.addEventListener('scroll', onScroll);


    //===== close navbar-collapse when a  clicked
    let navbarToggler = document.querySelector(".navbar-toggler");    
    var navbarCollapse = document.querySelector(".navbar-collapse");

    navbarToggler.addEventListener('click', () => {
        navbarToggler.classList.toggle('active')
    })

    document.querySelectorAll('.page-scroll').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });


    //===== glide tiny for testimonial
    
    tns({
        container: '.testimonial_active',
        items: 1,
        slideBy: 'page',
        autoplay: false,
        mouseDrag: true,
        gutter: 0,
        nav: true,
        controls: false,
    });

    //WOW Scroll Spy
    var wow = new WOW({
        //disabled for mobile
        mobile: false
    });
    wow.init();


        // ====== scroll top js
    function scrollTo(element, to = 0, duration= 1000) {

        const start = element.scrollTop;
        const change = to - start;
        const increment = 20;
        let currentTime = 0;

        const animateScroll = (() => {

            currentTime += increment;

            const val = Math.easeInOutQuad(currentTime, start, change, duration);

            element.scrollTop = val;

            if (currentTime < duration) {
                setTimeout(animateScroll, increment);
            }
        });

        animateScroll();
    };

    Math.easeInOutQuad = function (t, b, c, d) {

        t /= d/2;
        if (t < 1) return c/2*t*t + b;
        t--;
        return -c/2 * (t*(t-2) - 1) + b;
    };

    document.querySelector('.back-to-top').onclick = function () {
        scrollTo(document.documentElement); 
    }

    
})();