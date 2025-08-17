// Main JavaScript for Akshaya Patra Website

document.addEventListener('DOMContentLoaded', function() {
    initializeWebsite();
});

function initializeWebsite() {
    initNavbar();
    initHeroCarousel();
    initCounters();
    initStoriesCarousel();
    initDonationForm();
    initScrollToTop();
    initAnimations();
}

// Navbar functionality
function initNavbar() {
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function(e) {
            e.stopPropagation();
            navbarCollapse.classList.toggle('show');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                // If click is outside navbar and toggler
                if (!navbarCollapse.contains(e.target) && !navbarToggler.contains(e.target)) {
                    navbarCollapse.classList.remove('show');
                }
            }
        });
    }

    // Close mobile menu when clicking on links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                navbarCollapse.classList.remove('show');
            }
        });
    });
}

// Hero Carousel
function initHeroCarousel() {
    const slides = document.querySelectorAll('.hero-slide');
    const prevBtn = document.querySelector('.hero-prev');
    const nextBtn = document.querySelector('.hero-next');
    
    if (slides.length === 0) return;
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }
    
    // Auto-play carousel
    setInterval(nextSlide, 5000);
}

// Animated Counters
function initCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60 FPS
        
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format number with commas
            counter.textContent = Math.floor(current).toLocaleString();
        }, 16);
    };
    
    // Intersection Observer for triggering animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                if (!counter.classList.contains('animated')) {
                    counter.classList.add('animated');
                    animateCounter(counter);
                }
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        observer.observe(counter);
    });
}

// Stories Carousel
function initStoriesCarousel() {
    const storySlides = document.querySelectorAll('.story-slide');
    const prevBtn = document.querySelector('.stories-prev');
    const nextBtn = document.querySelector('.stories-next');
    
    if (storySlides.length === 0) return;
    
    let currentStory = 0;
    const totalStories = storySlides.length;
    
    function showStory(index) {
        storySlides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }
    
    function nextStory() {
        currentStory = (currentStory + 1) % totalStories;
        showStory(currentStory);
    }
    
    function prevStory() {
        currentStory = (currentStory - 1 + totalStories) % totalStories;
        showStory(currentStory);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextStory);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevStory);
    }
    
    // Auto-play stories
    setInterval(nextStory, 8000);
}

// Donation Form
function initDonationForm() {
    const donationBtns = document.querySelectorAll('[data-amount]');
    const customAmountInput = document.querySelector('.custom-amount-input');
    const customAmountField = document.getElementById('customAmount');
    const donateBtn = document.querySelector('.cta-section .btn-light');
    
    let selectedAmount = 500; // Default amount
    
    donationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            donationBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const amount = this.getAttribute('data-amount');
            
            if (amount === 'custom') {
                customAmountInput.style.display = 'block';
                customAmountField.focus();
                selectedAmount = null;
            } else {
                customAmountInput.style.display = 'none';
                selectedAmount = parseInt(amount);
            }
        });
    });
    
    if (customAmountField) {
        customAmountField.addEventListener('input', function() {
            selectedAmount = parseInt(this.value) || 0;
        });
    }
    
    if (donateBtn) {
        donateBtn.addEventListener('click', function() {
            const amount = selectedAmount || parseInt(customAmountField?.value) || 0;
            
            if (amount < 100) {
                showAlert('Please enter a minimum donation amount of ₹100', 'danger');
                return;
            }
            
            // Simulate donation process
            this.innerHTML = '<span class="spinner"></span> Processing...';
            this.disabled = true;
            
            setTimeout(() => {
                showAlert(`Thank you for your donation of ₹${amount.toLocaleString()}! Your contribution will help feed children in need.`, 'success');
                this.innerHTML = 'Donate Now';
                this.disabled = false;
            }, 2000);
        });
    }
}

// Scroll to Top
// function initScrollToTop() {
//     const scrollBtn = document.createElement('div');
//     scrollBtn.className = 'scroll-to-top';
//     scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
//     document.body.appendChild(scrollBtn);
    
//     window.addEventListener('scroll', function() {
//         if (window.scrollY > 300) {
//             scrollBtn.classList.add('visible');
//         } else {
//             scrollBtn.classList.remove('visible');
//         }
//     });
    
//     scrollBtn.addEventListener('click', function() {
//         window.scrollTo({
//             top: 0,
//             behavior: 'smooth'
//         });
//     });
// }

// Scroll Animations
function initAnimations() {
    const animatedElements = document.querySelectorAll('.card, .program-card, .story-slide, .blog-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        observer.observe(element);
    });
}

// Contact Form Validation
function validateContactForm(formData) {
    const errors = [];
    
    if (!formData.name || formData.name.trim().length < 2) {
        errors.push('Name must be at least 2 characters long');
    }
    
    if (!formData.email || !isValidEmail(formData.email)) {
        errors.push('Please enter a valid email address');
    }
    
    if (!formData.phone || formData.phone.trim().length < 10) {
        errors.push('Please enter a valid phone number');
    }
    
    if (!formData.message || formData.message.trim().length < 10) {
        errors.push('Message must be at least 10 characters long');
    }
    
    return errors;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Utility Functions
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-custom position-fixed`;
    alertDiv.style.cssText = `
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Animate in
    setTimeout(() => {
        alertDiv.style.opacity = '1';
        alertDiv.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(alertDiv);
        }, 300);
    }, 5000);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Form submission handlers
function handleFormSubmission(formElement, callback) {
    if (!formElement) return;
    
    formElement.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        if (callback) {
            callback(data, this);
        }
    });
}

// Loading state management
function setLoadingState(element, loading = true) {
    if (!element) return;
    
    if (loading) {
        element.classList.add('loading');
        element.disabled = true;
        const originalText = element.textContent;
        element.setAttribute('data-original-text', originalText);
        element.innerHTML = '<span class="spinner"></span> Loading...';
    } else {
        element.classList.remove('loading');
        element.disabled = false;
        const originalText = element.getAttribute('data-original-text');
        if (originalText) {
            element.textContent = originalText;
        }
    }
}

// Image lazy loading
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading
initLazyLoading();

// Export functions for use in other files
window.AkshayaPatra = {
    showAlert,
    setLoadingState,
    validateContactForm,
    isValidEmail,
    debounce,
    handleFormSubmission
};
