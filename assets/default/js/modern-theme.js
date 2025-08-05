// Modern Theme JavaScript for osTicket Client Side

document.addEventListener('DOMContentLoaded', function() {
    
    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading states to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('input[type="submit"], button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
            }
        });
    });

    // Add hover effects to navigation
    const navItems = document.querySelectorAll('#nav li a');
    navItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add fade-in animation to content
    const content = document.querySelector('#content');
    if (content) {
        content.style.opacity = '0';
        content.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            content.style.transition = 'all 0.6s ease-out';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        }, 100);
    }

    // Add smooth transitions to buttons
    document.querySelectorAll('.button, input[type="submit"], input[type="button"]').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add focus effects to form inputs
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Add mobile menu toggle functionality
    const createMobileMenu = () => {
        const nav = document.querySelector('#nav');
        if (nav && window.innerWidth <= 768) {
            const navList = nav.querySelector('ul');
            if (navList) {
                // Create mobile menu button
                const mobileBtn = document.createElement('button');
                mobileBtn.className = 'mobile-menu-btn';
                mobileBtn.innerHTML = '<span></span><span></span><span></span>';
                mobileBtn.style.cssText = `
                    display: none;
                    background: none;
                    border: none;
                    cursor: pointer;
                    padding: 10px;
                    position: absolute;
                    right: 10px;
                    top: 10px;
                    z-index: 1000;
                `;
                
                // Style the hamburger icon
                const spans = mobileBtn.querySelectorAll('span');
                spans.forEach((span, index) => {
                    span.style.cssText = `
                        display: block;
                        width: 25px;
                        height: 3px;
                        background: #1976d2;
                        margin: 5px 0;
                        transition: 0.3s;
                        border-radius: 2px;
                    `;
                });
                
                nav.appendChild(mobileBtn);
                
                // Add mobile styles
                nav.style.position = 'relative';
                navList.style.display = 'none';
                navList.style.position = 'absolute';
                navList.style.top = '100%';
                navList.style.left = '0';
                navList.style.right = '0';
                navList.style.background = '#fff';
                navList.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                navList.style.zIndex = '999';
                
                mobileBtn.style.display = 'block';
                
                // Toggle menu
                mobileBtn.addEventListener('click', function() {
                    const isOpen = navList.style.display === 'block';
                    navList.style.display = isOpen ? 'none' : 'block';
                    
                    // Animate hamburger
                    spans[0].style.transform = isOpen ? 'rotate(0deg)' : 'rotate(45deg) translate(5px, 5px)';
                    spans[1].style.opacity = isOpen ? '1' : '0';
                    spans[2].style.transform = isOpen ? 'rotate(0deg)' : 'rotate(-45deg) translate(7px, -6px)';
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!nav.contains(e.target)) {
                        navList.style.display = 'none';
                        spans[0].style.transform = 'rotate(0deg)';
                        spans[1].style.opacity = '1';
                        spans[2].style.transform = 'rotate(0deg)';
                    }
                });
            }
        }
    };

    // Initialize mobile menu
    createMobileMenu();
    
    // Reinitialize on window resize
    window.addEventListener('resize', createMobileMenu);

    // Add scroll effects to header
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const header = document.querySelector('#header');
        const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (currentScrollTop > lastScrollTop && currentScrollTop > 100) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = currentScrollTop;
    });

    // Add tooltip functionality
    document.querySelectorAll('[title]').forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('title');
            tooltip.style.cssText = `
                position: absolute;
                background: #333;
                color: white;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 12px;
                z-index: 1000;
                pointer-events: none;
                white-space: nowrap;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            `;
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
            
            this.addEventListener('mouseleave', function() {
                tooltip.remove();
            });
        });
    });

    // Add auto-hide for messages
    document.querySelectorAll('#msg_notice, #msg_warning, #msg_error, #msg_info').forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease-out';
            msg.style.opacity = '0';
            setTimeout(() => {
                msg.remove();
            }, 500);
        }, 5000);
    });

    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // Escape key to close modals or mobile menu
        if (e.key === 'Escape') {
            const mobileMenu = document.querySelector('#nav ul');
            if (mobileMenu && mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
            }
        }
    });

    // Add loading animation for AJAX requests
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'ajax-loading';
        loadingIndicator.innerHTML = '<div class="spinner"></div>';
        loadingIndicator.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 20px;
            border-radius: 8px;
            z-index: 10000;
            display: none;
        `;
        
        document.body.appendChild(loadingIndicator);
        
        return originalFetch.apply(this, args).finally(() => {
            loadingIndicator.remove();
        });
    };

    // Add CSS for spinner
    const spinnerCSS = `
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #1976d2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    
    const style = document.createElement('style');
    style.textContent = spinnerCSS;
    document.head.appendChild(style);

    console.log('Modern theme JavaScript loaded successfully!');
}); 