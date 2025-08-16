// Blog Display JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeBlogDisplay();
});

function initializeBlogDisplay() {
    loadPublicBlogs();
    initializeBlogFilters();
    initializeBlogSearch();
}

function loadPublicBlogs() {
    const blogs = getBlogsFromLocalStorage();
    const blogContainer = document.getElementById('blogContainer');
    
    if (!blogContainer) return;
    
    if (blogs.length === 0) {
        blogContainer.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-blog fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted">No blogs available yet</h3>
                    <p class="text-muted">Check back soon for inspiring stories and updates from Akshaya Patra.</p>
                </div>
            </div>
        `;
        return;
    }
    
    displayBlogs(blogs);
}

function displayBlogs(blogs) {
    const blogContainer = document.getElementById('blogContainer');
    
    blogContainer.innerHTML = blogs.map(blog => `
        <div class="col-lg-4 col-md-6 mb-4 blog-card-wrapper" data-category="${blog.category}">
            <div class="card blog-card h-100 border-0 shadow-sm">
                <img src="${blog.imageUrl}" class="card-img-top" alt="${blog.title}" 
                     style="height: 250px; object-fit: cover;"
                     onerror="this.onerror=null;this.src='images/default-${blog.category}.jpeg';">
                <div class="card-body d-flex flex-column">
                    <div class="blog-meta mb-2">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>${blog.author} | 
                            <i class="fas fa-calendar me-1"></i>${formatBlogDate(blog.publishDate)}
                        </small>
                        <span class="badge bg-primary ms-2">${formatCategory(blog.category)}</span>
                    </div>
                    <h5 class="card-title fw-bold">${blog.title}</h5>
                    <p class="card-text blog-excerpt flex-grow-1">${blog.excerpt}</p>
                    <div class="mt-auto">
                        <button class="btn btn-outline-primary" onclick="readFullBlog('${blog.id}')">
                            Read More <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    // Initialize animations
    animateBlogCards();
}

function initializeBlogFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const category = this.getAttribute('data-category');
            filterBlogs(category);
        });
    });
}

function filterBlogs(category) {
    const blogCards = document.querySelectorAll('.blog-card-wrapper');
    
    blogCards.forEach(card => {
        if (category === 'all' || card.getAttribute('data-category') === category) {
            card.style.display = 'block';
            card.style.animation = 'fadeInUp 0.6s ease';
        } else {
            card.style.display = 'none';
        }
    });
}

function initializeBlogSearch() {
    const searchInput = document.getElementById('blogSearch');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase().trim();
            searchBlogs(searchTerm);
        }, 300));
    }
}

function searchBlogs(searchTerm) {
    const blogs = getBlogsFromLocalStorage();
    
    if (!searchTerm) {
        displayBlogs(blogs);
        return;
    }
    
    const filteredBlogs = blogs.filter(blog => 
        blog.title.toLowerCase().includes(searchTerm) ||
        blog.content.toLowerCase().includes(searchTerm) ||
        blog.author.toLowerCase().includes(searchTerm) ||
        blog.category.toLowerCase().includes(searchTerm)
    );
    
    displayBlogs(filteredBlogs);
    
    // Show search results count
    const resultsCount = document.getElementById('searchResults');
    if (resultsCount) {
        resultsCount.innerHTML = `Found ${filteredBlogs.length} blog(s) for "${searchTerm}"`;
    }
}

function readFullBlog(blogId) {
    const blogs = getBlogsFromLocalStorage();
    const blog = blogs.find(b => b.id === blogId);
    
    if (!blog) {
        showBlogAlert('Blog not found', 'danger');
        return;
    }
    
    // Create modal for full blog content
    const modal = createBlogModal(blog);
    document.body.appendChild(modal);
    
    // Show modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    
    // Remove modal from DOM when hidden
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

function createBlogModal(blog) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = `blogModal-${blog.id}`;
    modal.setAttribute('tabindex', '-1');
    
    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">${blog.title}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="blog-meta mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <small class="text-muted">
                                    <i class="fas fa-user me-2"></i>${blog.author} | 
                                    <i class="fas fa-calendar me-2"></i>${formatBlogDate(blog.publishDate)} |
                                    <i class="fas fa-tag me-2"></i>${formatCategory(blog.category)}
                                </small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button class="btn btn-sm btn-outline-primary" onclick="shareBlog('${blog.id}')">
                                    <i class="fas fa-share-alt me-1"></i>Share
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <img src="${blog.imageUrl}" alt="${blog.title}" class="img-fluid rounded mb-4"
                         onerror="this.src='${getDefaultBlogImage(blog.category)}'">
                    
                    <div class="blog-content">
                        ${blog.content}
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <div class="blog-actions">
                                <button class="btn btn-outline-success me-2" onclick="likeBlog('${blog.id}')">
                                    <i class="fas fa-thumbs-up me-1"></i>Like
                                </button>
                                <button class="btn btn-outline-info" onclick="shareBlog('${blog.id}')">
                                    <i class="fas fa-share me-1"></i>Share
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    return modal;
}

function likeBlog(blogId) {
    // Get current likes from localStorage
    const likes = JSON.parse(localStorage.getItem('blog_likes') || '{}');
    
    // Toggle like status
    if (likes[blogId]) {
        delete likes[blogId];
        showBlogAlert('Like removed', 'info');
    } else {
        likes[blogId] = true;
        showBlogAlert('Thank you for liking this blog!', 'success');
    }
    
    // Save back to localStorage
    localStorage.setItem('blog_likes', JSON.stringify(likes));
}

function shareBlog(blogId) {
    const blogs = getBlogsFromLocalStorage();
    const blog = blogs.find(b => b.id === blogId);
    
    if (!blog) return;
    
    // Create share URL (in a real app, this would be the actual blog URL)
    const shareText = `Check out this inspiring blog: "${blog.title}" by ${blog.author}`;
    const shareUrl = `${window.location.origin}/blogs.html?blog=${blogId}`;
    
    // Check if Web Share API is supported
    if (navigator.share) {
        navigator.share({
            title: blog.title,
            text: shareText,
            url: shareUrl
        }).then(() => {
            showBlogAlert('Blog shared successfully!', 'success');
        }).catch(() => {
            fallbackShare(shareText, shareUrl);
        });
    } else {
        fallbackShare(shareText, shareUrl);
    }
}

function fallbackShare(text, url) {
    // Copy to clipboard
    const shareContent = `${text}\n${url}`;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(shareContent).then(() => {
            showBlogAlert('Blog link copied to clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = shareContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showBlogAlert('Blog link copied to clipboard!', 'success');
    }
}

function animateBlogCards() {
    const blogCards = document.querySelectorAll('.blog-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, { threshold: 0.1 });
    
    blogCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
}

// Utility functions
function getBlogsFromLocalStorage() {
    const blogsJson = localStorage.getItem('akshaya_patra_blogs');
    return blogsJson ? JSON.parse(blogsJson) : [];
}

function getDefaultBlogImage(category) {
    const defaultImages = {
        'impact-stories': 'https://pixabay.com/get/g3913e44124893ee08fda615d3f4b2affc0cf9c675cfd6122ecd09e1b69f85a4f7e1fd2c2be4598fc5b7f04e5b489083f75b561a1ccbe309703e1191a5b504501_1280.jpg',
        'news': 'https://pixabay.com/get/gcb89cd2c6748c93d1b4a00f606a8843899b7a38470ccfeb7c85b9252f440a54306b4f0f7e2712d6d743436fac38c6fa42934db75bde6f3aca78cea5fe78f04cf_1280.jpg',
        'programs': 'https://pixabay.com/get/gae869dcc4a514a3d23c8ce91e69b933bc6b9e67e43488e6de90ad6ee543a70ff7c7ab01f95969241d4b6f0a9908c71a73d0b6e2c535ea651ed631427cd4eec7c_1280.jpg',
        'events': 'https://pixabay.com/get/g80a49fc01aa07f3f4ba03f2ef18ed64aee32747dbc42386c256d9b32ec2cf556394ad12514d8ef8b9bc73eb8ceb90f91383c5a72534b88956995636ea6b1aab0_1280.jpg'
    };
    
    return defaultImages[category] || defaultImages['news'];
}

function formatBlogDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-IN', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatCategory(category) {
    return category.split('-').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
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

function showBlogAlert(message, type = 'success') {
    // Use the same alert function from main.js
    if (window.AkshayaPatra && window.AkshayaPatra.showAlert) {
        window.AkshayaPatra.showAlert(message, type);
    } else {
        // Fallback alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} position-fixed`;
        alertDiv.style.cssText = `
            top: 100px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            document.body.removeChild(alertDiv);
        }, 3000);
    }
}

// Handle blog URL parameters (for direct blog links)
function handleBlogUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const blogId = urlParams.get('blog');
    
    if (blogId) {
        setTimeout(() => {
            readFullBlog(blogId);
        }, 500);
    }
}

// Initialize URL params handling
handleBlogUrlParams();

// Export functions for global access
window.BlogDisplay = {
    readFullBlog,
    shareBlog,
    likeBlog,
    searchBlogs,
    filterBlogs
};
