(function() {
    'use strict';
    // Scroll to Top Button
    const scrollTopBtn = document.getElementById('scrollTop');
    
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });
        
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }


    // Bootstrap Carousel Auto-play Settings

    // Main carousel - slower transition
    const mainCarousel = document.getElementById('mainCarousel');
    if (mainCarousel) {
        const carousel = new bootstrap.Carousel(mainCarousel, {
            interval: 5000,
            wrap: true,
            keyboard: true,
            pause: 'hover'
        });
    }
    
    // Sponsor carousel - faster transition
    const sponsorCarousel = document.getElementById('sponsorCarousel');
    if (sponsorCarousel) {
        const carousel = new bootstrap.Carousel(sponsorCarousel, {
            interval: 4000,
            wrap: true,
            keyboard: false,
            pause: false
        });
    }
    
    // Member logos carousel - continuous scroll effect
    const memberCarousel = document.getElementById('memberLogosCarousel');
    if (memberCarousel) {
        const carousel = new bootstrap.Carousel(memberCarousel, {
            interval: 3000,
            wrap: true,
            keyboard: false,
            pause: 'hover'
        });
    }


    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = this.getAttribute('href');
            if (target !== '#' && target !== '#0') {
                const targetElement = document.querySelector(target);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });


    // Search Form Enhancement
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('.search-input');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }


    // Navbar Dropdown Enhancement for Mobile
    const dropdownToggles = document.querySelectorAll('.navbar .dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        // Prevent default link behavior on mobile
        if (window.innerWidth < 992) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
            });
        }
    });


    // Lazy Loading Images (if needed)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }


    // Add Animation on Scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.news-card, .event-item');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    // Initialize animation styles
    document.querySelectorAll('.news-card, .event-item').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);


    // Prevent dropdown close on click inside
    const dropdownMenus = document.querySelectorAll('.dropdown-menu');
    dropdownMenus.forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });


    // Mobile Menu Close on Link Click
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: true
                });
            }
        });
    });


    // Form Validation Enhancement
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
})();
