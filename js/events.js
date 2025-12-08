(function() {
    'use strict';

    // Event Category Filter
    const filterButtons = document.querySelectorAll('.filter-btn');
    const eventCards = document.querySelectorAll('.events-listing-section [data-category]');

    if (filterButtons.length > 0 && eventCards.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.getAttribute('data-category');

                // Update active state on buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Filter event cards with animation
                eventCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');

                    if (category === 'all' || cardCategory === category) {
                        // Show matching cards
                        card.style.display = '';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';

                        // Trigger animation
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        // Hide non-matching cards
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Initialize animation styles
        eventCards.forEach(card => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    }
    
    // Newsletter Form Submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('.newsletter-input');
            const email = emailInput.value.trim();
            
            if (email) {
                // Add your newsletter subscription logic here
                alert('Thank you for subscribing! You will receive event updates at ' + email);
                emailInput.value = '';
            }
        });
    }
    
    // Add scroll animation for event cards
    const observeEventCards = () => {
        if ('IntersectionObserver' in window) {
            const eventObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        eventObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            // Observe all event cards
            document.querySelectorAll('.event-card, .past-event-card, .benefit-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                eventObserver.observe(card);
            });
        }
    };
    
    // Initialize on page load
    window.addEventListener('load', observeEventCards);
    
    // Smooth scroll for register buttons
    document.querySelectorAll('.btn-event-register').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Add your registration logic here
            alert('Registration feature coming soon! This event looks interesting.');
        });
    });
    
})();
