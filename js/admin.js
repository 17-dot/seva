// Admin Panel JavaScript for Blog Management

// document.addEventListener('DOMContentLoaded', function() {
//     initializeAdmin();
// });

// function initializeAdmin() {
//     checkAdminSession();
//     initializeLoginForm();
//     initializeBlogManagement();
//     updateDashboardStats(); 
// }

document.addEventListener('DOMContentLoaded', function () {
    initializeAdmin();
});

function initializeAdmin() {
    checkAdminSession();
    initializeLoginForm();
    initializeBlogManagement();

    // Delay stats update slightly to ensure DOM ready and no overwrites
    setTimeout(updateDashboardStats, 500);
}


function checkAdminSession() {
    fetch("php/check_admin.php")
        .then(res => res.json())
        .then(data => {
            const loginForm = document.querySelector('.admin-login');
            const dashboard = document.querySelector('.admin-dashboard');
            const navbar = document.querySelector('.admin-dashboardd');

            if (data.logged_in) {
                // LOGGED IN – show dashboard and navbar, hide login
                if (loginForm) loginForm.style.display = 'none';
                if (navbar) navbar.style.display = 'flex';
                if (dashboard) dashboard.style.display = 'flex';
                loadBlogs();
            } else {
                // LOGGED OUT – show login, hide dashboard and navbar
                if (loginForm) loginForm.style.display = 'block';
                if (navbar) navbar.style.display = 'none';
                if (dashboard) dashboard.style.display = 'none';
            }
        })
        .catch(err => console.error('Session check failed', err));
}

function initializeLoginForm() {
    const loginForm = document.getElementById('adminLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            fetch("php/admin_login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showAlert('Login successful!', 'success');
                    setTimeout(checkAdminSession, 300);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(err => console.error('Login error:', err));
        });
    }
}

function logout() {
    fetch("php/logout.php")
        .then(res => res.json())
        .then(() => {
            showAlert('Logged out successfully!', 'success');
            setTimeout(checkAdminSession, 200);
        })
        .catch(err => console.error('Logout error:', err));
}



// function initializeBlogManagement() {
//     const blogForm = document.getElementById('blogForm');
//     const publishBtn = document.getElementById('publishBlog');

//     if (blogForm) {
//         blogForm.addEventListener("submit", function (e) {
//             e.preventDefault();

//             const blogData = {
//                 title: document.getElementById("blogTitle").value,
//                 category: document.getElementById("blogCategory").value,
//                 author: document.getElementById("blogAuthor").value,
//                 image_url: document.getElementById("blogImage").value,
//                 content: document.getElementById("blogContent").innerHTML // rich text
//             };

//             fetch("php/save_blog.php", {
//                 method: "POST",
//                 headers: { "Content-Type": "application/json" },
//                 body: JSON.stringify(blogData)
//             })
//             .then(res => res.json())
//             .then(data => {
//                 if (data.status === "success") {
//                     alert("Blog published!");
//                     blogForm.reset();
//                     // Reload blogs in UI after publishing
//                     if (typeof loadBlogs === 'function') {
//                         loadBlogs();
//                     }
//                 } else {
//                     alert("Error: " + data.message);
//                 }
//             })
//             .catch(err => console.error(err));
//         });
//     }

//     if (publishBtn) {
//         publishBtn.addEventListener('click', function() {
//             // Trigger form submission when publish button clicked
//             blogForm.dispatchEvent(new Event('submit'));
//         });
//     }

//     // Initialize rich text editor simulation
//     initializeRichTextEditor();
// }

function initializeBlogManagement() {
    const blogForm = document.getElementById('blogForm');

    if (blogForm) {
        // Remove any previously attached submit handlers before adding a new one
        // blogForm.onsubmit = function(e) {
        //     e.preventDefault();

        //     const blogData = {
        //         title: document.getElementById("blogTitle").value,
        //         category: document.getElementById("blogCategory").value,
        //         author: document.getElementById("blogAuthor").value,
        //         image_url: document.getElementById("blogImage").value,
        //         content: document.getElementById("blogContent").innerHTML
        //     };

        //     fetch("php/save_blog.php", {
        //         method: "POST",
        //         headers: { "Content-Type": "application/json" },
        //         body: JSON.stringify(blogData)
        //     })
        //     .then(res => res.json())
        //     .then(data => {
        //         if (data.status === "success") {
        //             alert("Blog published!");
        //             blogForm.reset();
        //             if (typeof loadBlogs === 'function') {
        //                 loadBlogs();
        //             }
        //         } else {
        //             alert("Error: " + data.message);
        //         }
        //     })
        //     .catch(err => console.error(err));
        // };

        blogForm.onsubmit = function(e) {
    e.preventDefault();
    const blogData = {
        title: document.getElementById("blogTitle").value,
        category: document.getElementById("blogCategory").value,
        author: document.getElementById("blogAuthor").value,
        image_url: document.getElementById("blogImage").value,  // this should be 'images/banner1.jpg'
        content: document.getElementById("blogContent").innerHTML
    };
    fetch("php/save_blog.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(blogData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert("Blog published!");
            blogForm.reset();
            if (typeof loadBlogs === 'function') {
                loadBlogs();
            }
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error(err));
};

    }

    // Remove unnecessary publish button click event handler
    // const publishBtn = document.getElementById('publishBlog');
    // if (publishBtn) {
    //     publishBtn.removeEventListener('click', ...); // Make sure no listener is attached here
    // }
    
    initializeRichTextEditor();
}

// Call this function on admin dashboard load



function initializeRichTextEditor() {
    const contentArea = document.getElementById('blogContent');
    const toolbar = document.querySelector('.editor-toolbar');
    
    if (!contentArea || !toolbar) return;
    
    // Add formatting buttons
    toolbar.innerHTML = `
        <div class="btn-group me-2" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('bold')">
                <i class="fas fa-bold"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('italic')">
                <i class="fas fa-italic"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('underline')">
                <i class="fas fa-underline"></i>
            </button>
        </div>
        <div class="btn-group me-2" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('insertUnorderedList')">
                <i class="fas fa-list-ul"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('insertOrderedList')">
                <i class="fas fa-list-ol"></i>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('createLink')">
                <i class="fas fa-link"></i>
            </button>
        </div>
    `;
    
    // Make content area editable
    contentArea.contentEditable = true;
    contentArea.style.minHeight = '200px';
    contentArea.style.border = '1px solid #ddd';
    contentArea.style.padding = '10px';
    contentArea.style.borderRadius = '5px';
}

function formatText(command) {
    const contentArea = document.getElementById('blogContent');
    
    if (command === 'createLink') {
        const url = prompt('Enter URL:');
        if (url) {
            document.execCommand(command, false, url);
        }
    } else {
        document.execCommand(command, false, null);
    }
    
    contentArea.focus();
}

function saveBlog() {
    const title = document.getElementById('blogTitle').value.trim();
    const author = document.getElementById('blogAuthor').value.trim();
    const category = document.getElementById('blogCategory').value;
    const content = document.getElementById('blogContent').innerHTML;
    const imageUrl = document.getElementById('blogImage').value.trim();
    
    // Validation
    if (!title || !author || !content) {
        showAlert('Please fill in all required fields', 'danger');
        return;
    }
    
    const blog = {
        id: Date.now().toString(),
        title,
        author,
        category,
        content,
        imageUrl: imageUrl || getDefaultImage(category),
        publishDate: new Date().toISOString(),
        excerpt: extractExcerpt(content)
    };
    
    // Save to localStorage
    const blogs = getBlogsFromStorage();
    blogs.unshift(blog); // Add to beginning
    localStorage.setItem('akshaya_patra_blogs', JSON.stringify(blogs));
    
    showAlert('Blog published successfully!', 'success');
    resetBlogForm();
    loadBlogs();
}

function extractExcerpt(content) {
    const textContent = content.replace(/<[^>]*>/g, ''); // Remove HTML tags
    return textContent.length > 150 ? textContent.substring(0, 150) + '...' : textContent;
}

function getDefaultImage(category) {
    const defaultImages = {
        'impact-stories': 'https://pixabay.com/get/g3913e44124893ee08fda615d3f4b2affc0cf9c675cfd6122ecd09e1b69f85a4f7e1fd2c2be4598fc5b7f04e5b489083f75b561a1ccbe309703e1191a5b504501_1280.jpg',
        'news': 'https://pixabay.com/get/gcb89cd2c6748c93d1b4a00f606a8843899b7a38470ccfeb7c85b9252f440a54306b4f0f7e2712d6d743436fac38c6fa42934db75bde6f3aca78cea5fe78f04cf_1280.jpg',
        'programs': 'https://pixabay.com/get/gae869dcc4a514a3d23c8ce91e69b933bc6b9e67e43488e6de90ad6ee543a70ff7c7ab01f95969241d4b6f0a9908c71a73d0b6e2c535ea651ed631427cd4eec7c_1280.jpg',
        'events': 'https://pixabay.com/get/g80a49fc01aa07f3f4ba03f2ef18ed64aee32747dbc42386c256d9b32ec2cf556394ad12514d8ef8b9bc73eb8ceb90f91383c5a72534b88956995636ea6b1aab0_1280.jpg'
    };
    
    return defaultImages[category] || defaultImages['news'];
}

function resetBlogForm() {
    document.getElementById('blogForm').reset();
    document.getElementById('blogContent').innerHTML = '';
}

// function loadBlogs() {
//     const blogs = getBlogsFromStorage();
//     const blogsList = document.getElementById('blogsList');
    
//     if (!blogsList) return;
    
//     if (blogs.length === 0) {
//         blogsList.innerHTML = `
//             <div class="text-center py-5">
//                 <i class="fas fa-blog fa-3x text-muted mb-3"></i>
//                 <h4 class="text-muted">No blogs published yet</h4>
//                 <p class="text-muted">Create your first blog post using the form above.</p>
//             </div>
//         `;
//         return;
//     }
    
//     blogsList.innerHTML = blogs.map(blog => `
//         <div class="blog-item" data-blog-id="${blog.id}">
//             <div class="row">
//                 <div class="col-md-3">
//                     <img src="${blog.imageUrl}" alt="${blog.title}" class="img-fluid rounded" 
//                          onerror="this.src='${getDefaultImage(blog.category)}'">
//                 </div>
//                 <div class="col-md-9">
//                     <h5 class="fw-bold">${blog.title}</h5>
//                     <p class="text-muted small">
//                         <i class="fas fa-user me-1"></i>${blog.author} | 
//                         <i class="fas fa-calendar me-1"></i>${formatDate(blog.publishDate)} |
//                         <i class="fas fa-tag me-1"></i>${blog.category}
//                     </p>
//                     <p class="blog-excerpt">${blog.excerpt}</p>
//                     <div class="blog-actions">
//                         <button class="btn btn-sm btn-outline-primary" onclick="editBlog('${blog.id}')">
//                             <i class="fas fa-edit me-1"></i>Edit
//                         </button>
//                         <button class="btn btn-sm btn-outline-danger" onclick="deleteBlog('${blog.id}')">
//                             <i class="fas fa-trash me-1"></i>Delete
//                         </button>
//                         <button class="btn btn-sm btn-outline-info" onclick="previewBlog('${blog.id}')">
//                             <i class="fas fa-eye me-1"></i>Preview
//                         </button>
//                     </div>
//                 </div>
//             </div>
//         </div>
//     `).join('');
// }

function loadBlogs() {
  fetch('php/get_blogs.php')
    .then(res => res.json())
    .then(blogs => {
      const blogsList = document.getElementById('blogsList');
      blogsList.innerHTML = '';

      if (!blogs.length) {
        blogsList.innerHTML = '<p class="text-muted">No blogs found.</p>';
        return;
      }

      blogs.forEach(blog => {
        const excerpt = blog.content.replace(/<[^>]*>/g, '').slice(0, 10) + '...';

        // blogsList.innerHTML += `
        //   <div class="d-flex border-bottom pb-2 mb-2">
        //     <img src="${blog.image_url}" class="rounded me-3"
        //          style="width:100px;height:70px;object-fit:cover;">
        //     <div>
        //       <h6 class="mb-1">${blog.author}</h6>
        //       <span class="badge bg-primary mb-1">${blog.category}</span>
        //       <div class="text-muted small">${excerpt}</div>
        //     </div>
        //   </div>
        // `;
//      blogsList.innerHTML += `
//   <div class="d-flex border-bottom pb-2 mb-2 position-relative">
//     <!-- Blog image -->
//     <img src="${blog.image_url}" class="rounded me-3"
//          style="width:100px;height:70px;object-fit:cover;">
    
//     <!-- Blog text content -->
//     <div class="flex-grow-1">
//       <div class="d-flex justify-content-between align-items-start">
//         <div>
//           <h6 class="mb-1">${blog.author}</h6>
//           <span class="badge bg-primary mb-1">${blog.category}</span>
//         </div>

//         <!-- Action icons -->
//         <div class="ms-2">
//           <button class="btn btn-sm btn-outline-secondary me-1" onclick="editBlog('${blog.id}')" title="Edit">
//             <i class="fas fa-edit"></i>
//           </button>
//           <button class="btn btn-sm btn-outline-danger" onclick="deleteBlog('${blog.id}')" title="Delete">
//             <i class="fas fa-trash"></i>
//           </button>
//         </div>
//       </div>

//       <!-- Excerpt -->
//       <div class="text-muted small">${excerpt}</div>
//     </div>
//   </div>
// `;

blogsList.innerHTML += `
  <div class="d-flex border-bottom pb-2 mb-2 position-relative">
    <!-- Blog image -->
    <img src="${blog.image_url}" class="rounded me-3"
         style="width:100px;height:70px;object-fit:cover;">

    <!-- Blog text content -->
    <div class="flex-grow-1">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="mb-1">${blog.author}</h6>
          <span class="badge bg-primary mb-1">${blog.category}</span>
        </div>

        <!-- Action icons -->
        <div class="ms-2 d-flex gap-2">
          <button
            class="btn btn-sm"
            onclick="editBlog('${blog.id}')"
            title="Edit"
            style="background-color: #007bff; color: white; padding: 0.2rem 0.4rem; font-size: 0.8rem; border-radius: 0.25rem; border: none; display: flex; align-items: center; justify-content: center;"
          >
            <i class="fas fa-edit"></i>
          </button>
          <button
            class="btn btn-sm"
            onclick="deleteBlog('${blog.id}')"
            title="Delete"
            style="background-color: black; color: white; padding: 0.2rem 0.4rem; font-size: 0.8rem; border-radius: 0.25rem; border: none; display: flex; align-items: center; justify-content: center;"
          >
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>

      <!-- Excerpt -->
      <div class="text-muted small">${excerpt}</div>
    </div>
  </div>
`;


    });
    })
    .catch(err => console.error('Error loading blogs', err));
}


// function editBlog(blogId) {
//     const blogs = getBlogsFromStorage();
//     const blog = blogs.find(b => b.id === blogId);
    
//     if (!blog) {
//         showAlert('Blog not found', 'danger');
//         return;
//     }
    
//     // Populate form with blog data
//     document.getElementById('blogTitle').value = blog.title;
//     document.getElementById('blogAuthor').value = blog.author;
//     document.getElementById('blogCategory').value = blog.category;
//     document.getElementById('blogContent').innerHTML = blog.content;
//     document.getElementById('blogImage').value = blog.imageUrl;
    
//     // Store current editing blog ID
//     document.getElementById('blogForm').setAttribute('data-editing-id', blogId);
    
//     // Change button text
//     const publishBtn = document.getElementById('publishBlog');
//     if (publishBtn) {
//         publishBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Blog';
//     }
    
//     // Scroll to form
//     document.getElementById('blogForm').scrollIntoView({ behavior: 'smooth' });
    
//     showAlert('Blog loaded for editing', 'success');
// }

// function deleteBlog(blogId) {
//     if (!confirm('Are you sure you want to delete this blog? This action cannot be undone.')) {
//         return;
//     }
    
//     const blogs = getBlogsFromStorage();
//     const updatedBlogs = blogs.filter(b => b.id !== blogId);
    
//     localStorage.setItem('akshaya_patra_blogs', JSON.stringify(updatedBlogs));
    
//     showAlert('Blog deleted successfully', 'success');
//     loadBlogs();
// }

function editBlog(id) {
    fetch(`php/get_blog.php?id=${id}`)
    .then(res => res.json())
    .then(blog => {
        if (blog.status === "error") {
            alert(blog.message);
        } else {
            document.getElementById("editBlogId").value = blog.id;
            document.getElementById("editBlogTitle").value = blog.title;
            document.getElementById("editBlogAuthor").value = blog.author;
            document.getElementById("editBlogCategory").value = blog.category;
            document.getElementById("editBlogImage").value = blog.image_url;
            document.getElementById("editBlogContent").innerHTML = blog.content;

            // Show Bootstrap Modal
            const modal = new bootstrap.Modal(document.getElementById('editBlogModal'));
            modal.show();
        }
    });
}


function deleteBlog(id) {
    if (!confirm("Are you sure you want to delete this blog?")) return;

    fetch("php/delete_blog.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") loadBlogs();
    });
}

function updateBlog() {
    const blogData = {
        id: document.getElementById("editBlogId").value,
        title: document.getElementById("editBlogTitle").value,
        author: document.getElementById("editBlogAuthor").value,
        category: document.getElementById("editBlogCategory").value,
        image_url: document.getElementById("editBlogImage").value,
        content: document.getElementById("editBlogContent").innerHTML
    };

    fetch("php/update_blog.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(blogData)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editBlogModal'));
            modal.hide();
            loadBlogs();
        }
    });
}


function previewBlog(blogId) {
    const blogs = getBlogsFromStorage();
    const blog = blogs.find(b => b.id === blogId);
    
    if (!blog) {
        showAlert('Blog not found', 'danger');
        return;
    }
    
    // Open blog in new window/tab
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>${blog.title} - Akshaya Patra</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Poppins', sans-serif; }
                .blog-header { background: linear-gradient(135deg, #FF6900, #2E86C1); color: white; padding: 3rem 0; }
                .blog-content { padding: 2rem 0; }
                .blog-content img { max-width: 100%; height: auto; }
            </style>
        </head>
        <body>
            <div class="blog-header">
                <div class="container">
                    <h1 class="display-4 fw-bold">${blog.title}</h1>
                    <p class="lead">
                        <i class="fas fa-user me-2"></i>${blog.author} | 
                        <i class="fas fa-calendar me-2"></i>${formatDate(blog.publishDate)} |
                        <i class="fas fa-tag me-2"></i>${blog.category}
                    </p>
                </div>
            </div>
            <div class="blog-content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <img src="${blog.imageUrl}" alt="${blog.title}" class="img-fluid rounded mb-4">
                            <div class="blog-text">
                                ${blog.content}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    `);
    previewWindow.document.close();
}

// Blog storage functions
function getBlogsFromStorage() {
    const blogsJson = localStorage.getItem('akshaya_patra_blogs');
    return blogsJson ? JSON.parse(blogsJson) : [];
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-IN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function showAlert(message, type = 'success') {
    // Use the same alert function from main.js
    if (window.AkshayaPatra && window.AkshayaPatra.showAlert) {
        window.AkshayaPatra.showAlert(message, type);
    } else {
        // Fallback alert
        alert(message);
    }
}

function updateDashboardStats() {
    fetch('php/get_dashboard_stats.php')
        .then(res => res.json())
        .then(data => {
            console.log('Dashboard data fetched:', data);
            if(document.getElementById('totalBlogs')) {
                document.getElementById('totalBlogs').textContent = data.total_blogs ?? 0;
                console.log('Total Blogs updated to:', data.total_blogs);
            }
            if(document.getElementById('todayBlogs')) {
                document.getElementById('todayBlogs').textContent = data.published_today ?? 0;
                console.log('Today Blogs updated to:', data.published_today);
            }
            if(document.getElementById('impactStories')) {
                document.getElementById('impactStories').textContent = data.impact_stories ?? 0;
                console.log('Impact Stories updated to:', data.impact_stories);
            }
            if(document.getElementById('newsArticles')) {
                document.getElementById('newsArticles').textContent = data.news_articles ?? 0;
                console.log('News Articles updated to:', data.news_articles);
            }
        })
        .catch(err => {
            console.error('Dashboard load error:', err);
        });
}


// Export functions for global access
window.AdminPanel = {
    logout,
    editBlog,
    deleteBlog,
    previewBlog,
    formatText,
    getBlogsFromStorage
};

document.addEventListener('DOMContentLoaded', function() {
    checkAdminSession();
    initializeLoginForm();
    initializeBlogManagement();
    updateDashboardStats();
});


