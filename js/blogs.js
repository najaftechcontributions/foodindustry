(function() {
    'use strict';
    
    // Blog Category Filter
    const filterButtons = document.querySelectorAll('.filter-btn');
    const blogCards = document.querySelectorAll('.blogs-listing-section [data-category]');

    if (filterButtons.length > 0 && blogCards.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.getAttribute('data-category');

                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Filter blog cards
                blogCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');

                    if (category === 'all' || cardCategory === category) {
                        card.style.display = '';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Initialize blog cards with transition styles
        blogCards.forEach(card => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        });
    }
    
    // Copy Link to Clipboard
    const copyLinkBtn = document.querySelector('.copy-link');
    
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = window.location.href;
            
            // Modern clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    showCopyFeedback(this);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    fallbackCopyText(url, this);
                });
            } else {
                fallbackCopyText(url, this);
            }
        });
    }
    
    // Fallback copy method for older browsers
    function fallbackCopyText(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.select();
        
        try {
            document.execCommand('copy');
            showCopyFeedback(button);
        } catch (err) {
            console.error('Fallback copy failed:', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    // Show copy feedback
    function showCopyFeedback(button) {
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-check"></i>';
        button.style.background = '#28a745';
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.style.background = '';
        }, 2000);
    }
    
    // Newsletter Form Submission
    const newsletterForms = document.querySelectorAll('.newsletter-form, .sidebar-newsletter-form');
    
    newsletterForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (emailInput && emailInput.value.trim()) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Subscribing...';
                submitBtn.disabled = true;
                
                // Simulate subscription (replace with actual API call)
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Subscribed!';
                    submitBtn.style.background = '#28a745';
                    emailInput.value = '';
                    
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        submitBtn.disabled = false;
                    }, 3000);
                }, 1500);
            }
        });
    });
    
    // Comment Form Submission
    const commentForm = document.querySelector('.comment-form');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const textarea = this.querySelector('textarea');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (textarea && textarea.value.trim()) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Posting...';
                submitBtn.disabled = true;
                
                // Simulate comment posting (replace with actual API call)
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Posted!';
                    submitBtn.style.background = '#28a745';
                    textarea.value = '';
                    
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        submitBtn.disabled = false;
                    }, 2000);
                }, 1500);
            }
        });
    }
    
    // Reading Progress Bar (for blog detail page)
    if (document.querySelector('.blog-article-content')) {
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress-bar';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            z-index: 9999;
            transition: width 0.2s ease;
        `;
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    }
    
    // Smooth scroll for anchor links in blog content
    document.querySelectorAll('.blog-article-content a[href^="#"]').forEach(anchor => {
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
    
    // Share buttons functionality
    const shareButtons = document.querySelectorAll('.share-btn');
    
    shareButtons.forEach(button => {
        if (button.classList.contains('facebook')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = window.location.href;
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
            });
        }
        
        if (button.classList.contains('twitter')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = window.location.href;
                const title = document.querySelector('.blog-detail-title')?.textContent || document.title;
                window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`, '_blank', 'width=600,height=400');
            });
        }
        
        if (button.classList.contains('linkedin')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = window.location.href;
                window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
            });
        }
        
        if (button.classList.contains('email')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = window.location.href;
                const title = document.querySelector('.blog-detail-title')?.textContent || document.title;
                window.location.href = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}`;
            });
        }
    });
    
    // Add fade-in animation to blog cards on scroll
    const observeElements = document.querySelectorAll('.blog-card, .featured-blog-section');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        observeElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });
    }
    
})();
