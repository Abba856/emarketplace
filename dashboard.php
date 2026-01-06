<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>E-marketplace Dashboard</title>
  <style>
  /* Desktop / base styles (keep your existing desktop rules) */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: #f9f9f9;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
  }

  header {
    background: linear-gradient(to right, #2d5a27, #3d7a35);
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  header h1 { margin: 0; font-size: 1.25rem; }
  header a { color: white; text-decoration: none; margin-right: 12px; }
  header button { padding: 10px 20px; background: #f28c28; border: none; cursor: pointer; color: white; border-radius: 5px; font-size: 1rem; }

  #filter {
    padding: 15px;
    background: white;
    display: flex;
    gap: 15px;
    align-items: center;
    margin: 20px 5%;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    padding: 20px;
  }

  .card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .card img, .card video { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; display: block; }

  .modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
    box-sizing: border-box;
  }

  .modal-content {
    background: white;
    padding: 25px;
    border-radius: 10px;
    width: 600px;
    max-width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-sizing: border-box;
  }

  .modal-content input,
  .modal-content select,
  .modal-content textarea {
    width: 100%;
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1rem;
  }

  .close { float: right; cursor: pointer; font-size: 24px; color: #888; }

  .success-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #4CAF50;
    color: white;
    padding: 12px 16px;
    border-radius: 6px;
    z-index: 1001;
    display: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  }

  /* -------------------
     Responsive: Tablet
     ------------------- */
  @media (max-width: 992px) {
    header { padding: 12px 18px; }
    .modal-content { width: 520px; padding: 22px; }
    .card img, .card video { height: 180px; }
  }

  /* -------------------
     Responsive: Mobile
     ------------------- */
  @media (max-width: 768px) {
    header {
      flex-direction: column;
      gap: 10px;
      padding: 12px;
      align-items: stretch;
      text-align: center;
    }

    header h1 { font-size: 1.15rem; }
    header div { display: flex; flex-direction: column; gap: 8px; align-items: center; width: 100%; }
    header a { margin-right: 0; display: inline-block; padding: 6px 8px; }
    header button { width: 100%; max-width: 300px; margin: 6px auto 0; }

    #filter {
      flex-direction: column;
      align-items: stretch;
      margin: 12px 4%;
      padding: 12px;
    }

    #filter label { font-size: 0.95rem; margin-bottom: 6px; }
    #filter select { width: 100%; padding: 10px; font-size: 0.95rem; }

    .grid {
      grid-template-columns: 1fr;
      gap: 16px;
      padding: 12px;
    }

    .card {
      padding: 16px;
    }

    .card img, .card video {
      height: 220px;
      border-radius: 8px;
    }

    .modal {
      align-items: flex-start;
      padding-top: 30px;
    }

    .modal-content {
      width: 100%;
      max-width: 480px;
      margin: 0 auto;
      padding: 18px;
      border-radius: 10px;
    }

    .modal-content input,
    .modal-content select,
    .modal-content textarea {
      padding: 12px;
      font-size: 1rem;
    }

    .close { font-size: 22px; }

    button {
      padding: 12px 14px;
      font-size: 1rem;
      border-radius: 8px;
    }

    .success-message {
      top: 14px;
      right: 12px;
      padding: 10px 12px;
      font-size: 0.95rem;
    }
  }

  /* -------------------
     Small Mobile
     ------------------- */
  @media (max-width: 480px) {
    header h1 { font-size: 1rem; }
    header div { gap: 6px; }
    .grid { padding: 8px; gap: 12px; }
    .card img, .card video { height: 180px; }

    .modal-content {
      padding: 14px;
      max-height: 85vh;
    }

    .modal-content input,
    .modal-content select,
    .modal-content textarea {
      padding: 10px;
      font-size: 0.95rem;
    }

    .form-group label { font-size: 0.95rem; }

    /* Make primary button full width inside modal */
    .modal-content button,
    header button {
      width: 100%;
      box-sizing: border-box;
    }
  }

  /* -------------------
     Extra small phones
     ------------------- */
  @media (max-width: 360px) {
    body { font-size: 14px; }
    .card img, .card video { height: 160px; }
    .modal-content { padding: 12px; }
    .modal-content input, .modal-content select, .modal-content textarea { padding: 8px; }
  }

  /* For touch devices - reduce hover transitions */
  @media (hover: none) and (pointer: coarse) {
    .card { transition: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .card:hover { transform: none; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
    button:hover { background: #f28c28; }
  }
  </style>
</head>
<body>

<header>
  <h1>🌾 E-Marketplace for Local Farmers</h1>
  <div>
    <span style="color: white; margin-right: 20px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    <a href="index.php" style="color: white; text-decoration: none; margin-right: 20px;">View Products</a>
    <a href="logout.php" style="color: white; text-decoration: none; margin-right: 20px;">Logout</a>
    <button onclick="openModal()">+ Add Product</button>
  </div>
</header>

<section id="filter">
  <label>Filter by Category:</label>
  <select onchange="filterProducts(this.value)">
    <option value="all">All Products</option>
    <option value="fruit">Fruits</option>
    <option value="vegetable">Vegetables</option>
    <option value="grain">Grains</option>
    <option value="dairy">Dairy</option>
    <option value="livestock">Livestock</option>
  </select>
</section>

<section id="products" class="grid"></section>

<!-- Add Product Modal -->
<div id="modal" class="modal">
  <div class="modal-content">
    <span onclick="closeModal()" class="close">&times;</span>
    <h2>Add Product</h2>
    <div class="form-group">
      <label for="pName">Product Name *</label>
      <input type="text" id="pName" placeholder="Enter product name">
    </div>
    <div class="form-group">
      <label for="pCategory">Category *</label>
      <select id="pCategory">
        <option value="fruit">Fruits</option>
        <option value="vegetable">Vegetables</option>
        <option value="grain">Grains</option>
        <option value="dairy">Dairy</option>
        <option value="livestock">Livestock</option>
      </select>
    </div>
    <div class="form-group">
      <label for="pPrice">Price (₦) *</label>
      <input type="number" id="pPrice" placeholder="Enter price">
    </div>
    <div class="form-group">
      <label for="pMedia">Upload Image/Video</label>
      <input type="file" id="pMedia" accept="image/*,video/*" onchange="validateAndPreviewMedia(this)">
      <small>Upload image or short video (max 2MB)</small>
      <div id="mediaPreview" style="margin-top:10px;"></div>
      <div id="fileError" style="color: #d32f2f; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
    </div>
    <div class="form-group">
      <label for="farmerName">Farmer Name *</label>
      <input type="text" id="farmerName" placeholder="Enter farmer name">
    </div>
    <div class="form-group">
      <label for="phoneNumber">Contact me *</label>
      <input type="text" id="phoneNumber" placeholder="Enter contact information">
    </div>
    <div class="form-group">
      <label for="location">Location *</label>
      <input type="text" id="location" placeholder="Enter location">
    </div>
    <div class="form-group">
      <label for="pDesc">Description *</label>
      <textarea id="pDesc" placeholder="Enter product description" rows="4"></textarea>
    </div>
    <div class="form-group">
      <input type="checkbox" id="confirmFarm" required>
      <label for="confirmFarm" style="display: inline;">Confirm this is a farm product *</label>
    </div>
    <button onclick="addProduct()">Submit Product</button>
  </div>
</div>

<div class="success-message" id="successMessage">
  Operation completed successfully!
</div>

<script>
  let currentFilter = "all";

  function openModal() {
    document.getElementById("modal").style.display = "flex";
  }

  function closeModal() {
    document.getElementById("modal").style.display = "none";
    clearForm();
  }

  function clearForm() {
    document.getElementById("pName").value = '';
    document.getElementById("pCategory").value = 'fruit';
    document.getElementById("pPrice").value = '';
    document.getElementById("pMedia").value = '';
    document.getElementById("farmerName").value = '';
    document.getElementById("phoneNumber").value = '';
    document.getElementById("location").value = '';
    document.getElementById("pDesc").value = '';
    document.getElementById("mediaPreview").innerHTML = '';
  }

  function addProduct() {
    const name = document.getElementById("pName").value;
    const category = document.getElementById("pCategory").value;
    const price = document.getElementById("pPrice").value;
    const media = document.getElementById("pMedia").files[0];
    const farmerName = document.getElementById("farmerName").value;
    const phone = document.getElementById("phoneNumber").value;
    const location = document.getElementById("location").value;
    const description = document.getElementById("pDesc").value;

    if (!name || !price || !farmerName || !phone || !location || !description) {
      alert("Please fill all required fields.");
      return;
    }

    // Update button to show loading state
    const submitBtn = document.querySelector('#modal button[onclick="addProduct()"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Uploading...";
    submitBtn.disabled = true;

    // Create form data for AJAX request
    const formData = new FormData();
    formData.append('name', name);
    formData.append('category', category);
    formData.append('price', price);
    formData.append('phone', phone);
    formData.append('location', location);
    formData.append('description', description);
    formData.append('farmer_name', farmerName);

    if (media) {
      // Validate file type again as a double-check
      const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/ogg'];
      if (!validTypes.includes(media.type)) {
        alert("Invalid file type. Please upload JPG, PNG, GIF, MP4, WEBM, or OGG files.");
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        return;
      }

      formData.append('media', media);
    }

    // Send AJAX request to add product
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_product.php', true);

    xhr.onload = function() {
      // Restore button state
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;

      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Show success message
          document.getElementById("successMessage").style.display = "block";
          setTimeout(function() {
            document.getElementById("successMessage").style.display = "none";
          }, 3000);

          // Reload products
          loadProducts(currentFilter);

          // Close modal
          closeModal();
        } else {
          alert("Error: " + response.message);
        }
      } else {
        alert("An error occurred while adding the product. Please try again.");
      }
    };

    xhr.onerror = function() {
      // Restore button state
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
      alert("Network error occurred. Please check your connection and try again.");
    };

    xhr.send(formData);
  }

  function loadProducts(filter = "all") {
    // Show loading indicator
    const container = document.getElementById("products");
    container.innerHTML = "<p style='padding: 20px; text-align: center; font-size: 1.2rem; grid-column: 1 / -1;'>Loading products...</p>";

    // Make AJAX request to fetch products
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_products.php?filter=' + filter, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        const products = JSON.parse(xhr.responseText);
        displayProducts(products);
      }
    };
    xhr.send();
  }

  function displayProducts(products) {
    const container = document.getElementById("products");
    container.innerHTML = "";

    if (products.length === 0) {
      container.innerHTML = "<p style='padding: 20px; text-align: center; font-size: 1.2rem; grid-column: 1 / -1;'>No products found.</p>";
      return;
    }

    products.forEach(p => {
      const card = document.createElement("div");
      card.className = "card";

      let mediaHTML = '';
      if (p.media_url) {
        if (p.media_type && p.media_type.startsWith("image")) {
          mediaHTML = `<img src="${p.media_url}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" alt="${p.name}">`;
        } else if (p.media_type && p.media_type.startsWith("video")) {
          mediaHTML = `<video controls style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                         <source src="${p.media_url}" type="${p.media_type}">
                         Your browser does not support the video tag.
                       </video>`;
        }
      } else {
        mediaHTML = `<img src="https://placehold.co/400x300?text=No+Image" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" alt="${p.name}">`;
      }

      card.innerHTML = `
        ${mediaHTML}
        <span class="category">${p.category.charAt(0).toUpperCase() + p.category.slice(1)}</span>
        <h3>${p.name}</h3>
        <p class="price">₦${p.price}</p>
        <p><strong>Farmer:</strong> ${p.farmer_name || 'N/A'}</p>
        <p><strong>Contact me:</strong> ${p.phone}</p>
        <p><strong>Location:</strong> ${p.location}</p>
        <p>${p.description}</p>
        <div style="margin-top: 15px; text-align: right;">
          <button onclick="deleteProduct(${p.id})" style="background: #d32f2f; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Delete</button>
        </div>
      `;

      container.appendChild(card);
    });
  }

  function filterProducts(filter) {
    currentFilter = filter;
    loadProducts(filter);
  }

  function validateAndPreviewMedia(input) {
    const file = input.files[0];
    const errorDiv = document.getElementById("fileError");
    const preview = document.getElementById("mediaPreview");

    // Clear previous preview and errors
    errorDiv.style.display = "none";
    errorDiv.textContent = "";
    preview.innerHTML = "";

    if (file) {
      // Validate file size
      if (file.size > 2 * 1024 * 1024) {
        errorDiv.style.display = "block";
        errorDiv.textContent = "File size exceeds 2MB limit. Please choose a smaller file.";
        input.value = ""; // Clear the input
        return false;
      }

      // Validate file type
      const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/ogg'];
      if (!validTypes.includes(file.type)) {
        errorDiv.style.display = "block";
        errorDiv.textContent = "Invalid file type. Please upload JPG, PNG, GIF, MP4, WEBM, or OGG files.";
        input.value = ""; // Clear the input
        return false;
      }

      // Create preview
      const reader = new FileReader();
      reader.onload = function(e) {
        if (file.type.startsWith("image")) {
          preview.innerHTML = `<div style="margin-top: 10px;"><img src="${e.target.result}" style="max-width: 100%; height: auto; border-radius: 8px;" alt="Preview"></div>`;
        } else if (file.type.startsWith("video")) {
          preview.innerHTML = `<div style="margin-top: 10px;"><video controls style="max-width: 100%; height: auto; border-radius: 8px;">
                                 <source src="${e.target.result}" type="${file.type}">
                               </video></div>`;
        }
      };
      reader.readAsDataURL(file);
    }
  }

  function deleteProduct(productId) {
    if (!confirm("Are you sure you want to delete this product? This action cannot be undone.")) {
      return;
    }

    // Create form data for AJAX request
    const formData = new FormData();
    formData.append('product_id', productId);

    // Send AJAX request to delete product
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_product.php', true);

    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Show success message
          document.getElementById("successMessage").style.display = "block";
          document.getElementById("successMessage").textContent = response.message;
          setTimeout(function() {
            document.getElementById("successMessage").style.display = "none";
          }, 3000);

          // Reload products
          loadProducts(currentFilter);
        } else {
          alert("Error: " + response.message);
        }
      } else {
        alert("An error occurred while deleting the product. Please try again.");
      }
    };

    xhr.onerror = function() {
      alert("Network error occurred. Please check your connection and try again.");
    };

    xhr.send(formData);
  }

  // On page load
  closeModal();
  loadProducts();
</script>

</body>
</html>
