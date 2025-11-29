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
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f9f9f9;
    }

    header {
      background: linear-gradient(to right, #2d5a27, #3d7a35);
      color: white;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    button {
      padding: 10px 20px;
      background: #f28c28;
      border: none;
      cursor: pointer;
      color: white;
      border-radius: 5px;
      font-size: 1rem;
    }
    
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

    .card h3 {
      margin-top: 0;
      color: #2d5a27;
    }
    
    .price {
      font-weight: bold;
      color: #f28c28;
      font-size: 1.1rem;
    }
    
    .category {
      display: inline-block;
      background: #e8f5e9;
      color: #2d5a27;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      margin-bottom: 10px;
    }
    
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      padding: 25px;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      max-height: 90vh;
      overflow-y: auto;
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
    }

    .close {
      float: right;
      cursor: pointer;
      font-size: 24px;
      color: #888;
    }
    
    .close:hover {
      color: #333;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    
    .success-message {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #4CAF50;
      color: white;
      padding: 15px;
      border-radius: 5px;
      z-index: 1001;
      display: none;
    }
    
    .error-message {
      color: #d32f2f;
      font-size: 0.9rem;
      margin-top: 5px;
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
      <input type="file" id="pMedia" accept="image/*,video/*">
      <small>Upload image or short video (max ~5MB)</small>
      <div id="mediaPreview" style="margin-top:10px;"></div>
    </div>
    <div class="form-group">
      <label for="phoneNumber">Phone Number *</label>
      <input type="text" id="phoneNumber" placeholder="Enter phone number">
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
  Product added successfully!
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
    const phone = document.getElementById("phoneNumber").value;
    const location = document.getElementById("location").value;
    const description = document.getElementById("pDesc").value;

    if (!name || !price || !description || !phone || !location) {
      alert("Please fill all required fields.");
      return;
    }

    // Create form data for AJAX request
    const formData = new FormData();
    formData.append('name', name);
    formData.append('category', category);
    formData.append('price', price);
    formData.append('phone', phone);
    formData.append('location', location);
    formData.append('description', description);

    if (media) {
      formData.append('media', media);
    }

    // Send AJAX request to add product
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_product.php', true);
    
    xhr.onload = function() {
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
        alert("An error occurred while adding the product.");
      }
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
        <p><strong>Phone:</strong> ${p.phone}</p>
        <p><strong>Location:</strong> ${p.location}</p>
        <p>${p.description}</p>
      `;

      container.appendChild(card);
    });
  }

  function filterProducts(filter) {
    currentFilter = filter;
    loadProducts(filter);
  }

  document.getElementById("pMedia").addEventListener("change", function () {
    const file = this.files[0];
    const preview = document.getElementById("mediaPreview");

    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        if (file.type.startsWith("image")) {
          preview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; height: auto; border-radius: 8px;" alt="Preview">`;
        } else if (file.type.startsWith("video")) {
          preview.innerHTML = `<video controls style="max-width: 100%; height: auto; border-radius: 8px;">
                                 <source src="${e.target.result}" type="${file.type}">
                               </video>`;
        } else {
          preview.innerHTML = "Unsupported file type.";
        }
      };
      reader.readAsDataURL(file);
    } else {
      preview.innerHTML = "";
    }
  });

  // On page load
  closeModal();
  loadProducts();
</script>

</body>
</html>
