<!DOCTYPE html>
<html lang="en">
<head>
  <title>AgriMarketplace - Buy Fresh Farm Produce</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      color: #333;
    }

    header {
      background: linear-gradient(to right, #2d5a27, #3d7a35);
      color: white;
      padding: 15px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo h1 {
      font-size: 1.8rem;
      margin: 0;
    }

    .logo-icon {
      font-size: 2rem;
    }

    nav ul {
      display: flex;
      list-style: none;
      gap: 25px;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #ffd700;
    }

    .hero {
      position: relative;
      height: 500px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      padding: 20px;
      overflow: hidden;
    }
    
    .hero-slides {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }
    
    .hero-slides.active {
      opacity: 1;
    }
    
    .hero-content {
      position: relative;
      z-index: 2;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
    }

    .hero h2 {
      font-size: 2.5rem;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .hero p {
      font-size: 1.2rem;
      max-width: 700px;
      margin-bottom: 30px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .btn {
      padding: 12px 25px;
      background: #f28c28;
      border: none;
      cursor: pointer;
      color: white;
      border-radius: 5px;
      font-size: 1rem;
      text-decoration: none;
      display: inline-block;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #e07c1a;
    }

    .features {
      padding: 60px 5%;
      background: white;
    }

    .features h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 2rem;
      color: #2d5a27;
    }

    .features-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }

    .feature-card {
      background: #f0f7f0;
      padding: 25px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .feature-card:hover {
      transform: translateY(-5px);
    }

    .feature-card h3 {
      color: #2d5a27;
      margin-bottom: 15px;
    }

    .feature-card p {
      color: #555;
    }

    .products-section {
      padding: 60px 5%;
      background: #f0f7f0;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .section-header h2 {
      font-size: 2rem;
      color: #2d5a27;
    }

    #filter {
      padding: 15px;
      background: white;
      display: flex;
      gap: 15px;
      align-items: center;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    #filter label {
      font-weight: bold;
      color: #333;
    }

    #filter select {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 25px;
      padding: 10px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .card img, .card video {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .card h3 {
      margin: 10px 0 10px 0;
      color: #2d5a27;
      font-size: 1.3rem;
    }

    .card p {
      margin: 8px 0;
      color: #555;
    }

    .price {
      font-weight: bold;
      color: #f28c28;
      font-size: 1.2rem;
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

    footer {
      background: #2d5a27;
      color: white;
      padding: 40px 5% 20px;
      margin-top: 60px;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      margin-bottom: 30px;
    }

    .footer-column h3 {
      margin-bottom: 20px;
      font-size: 1.3rem;
      color: #ffd700;
    }

    .footer-column ul {
      list-style: none;
    }

    .footer-column ul li {
      margin-bottom: 10px;
    }

    .footer-column a {
      color: #ddd;
      text-decoration: none;
    }

    .footer-column a:hover {
      color: #ffd700;
    }

    .copyright {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.1);
      color: #aaa;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
      
      nav {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      nav ul {
        display: flex;
        flex-direction: column;
        text-align: center;
        gap: 10px;
        width: 100%;
      }
      
      nav li {
        width: 100%;
      }

      .hero {
        height: 40vh;
        min-height: 300px;
      }

      .hero h2 {
        font-size: 1.8rem;
        margin-bottom: 10px;
      }
      
      .hero p {
        font-size: 1rem;
        margin-bottom: 20px;
      }
      
      .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
      
      .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }
      
      .section-header h2 {
        font-size: 1.5rem;
      }
      
      .features {
        padding: 30px 5%;
      }
      
      .features h2 {
        font-size: 1.5rem;
      }
      
      .features-container {
        grid-template-columns: 1fr;
        gap: 20px;
      }
      
      .feature-card {
        padding: 20px;
      }
      
      .products-section {
        padding: 30px 5%;
      }
      
      #filter {
        flex-direction: column;
        align-items: stretch;
        padding: 10px;
      }
      
      #filter label {
        text-align: left;
      }
      
      #filter select {
        width: 100%;
      }
      
      .grid {
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 5px;
      }
      
      .card img, .card video {
        height: 180px;
      }
      
      .card {
        padding: 15px;
      }
      
      .card h3 {
        font-size: 1.2rem;
      }
      
      .footer-content {
        grid-template-columns: 1fr;
        gap: 20px;
      }
      
      footer {
        padding: 30px 5% 15px;
      }
    }
    
    /* Extra small devices */
    @media (max-width: 480px) {
      body {
        font-size: 0.9rem;
      }
      
      .hero {
        height: 35vh;
        min-height: 250px;
      }
      
      .hero h2 {
        font-size: 1.5rem;
      }
      
      .hero p {
        font-size: 0.9rem;
      }
      
      .logo h1 {
        font-size: 1.5rem;
      }
      
      .features h2 {
        font-size: 1.3rem;
      }
      
      .section-header h2 {
        font-size: 1.3rem;
      }
      
      .card img, .card video {
        height: 150px;
      }
      
      .card h3 {
        font-size: 1.1rem;
      }
      
      .category {
        font-size: 0.7rem;
      }
      
      .price {
        font-size: 1.1rem;
      }
      
      .feature-card {
        padding: 15px;
      }
    }
    
    /* For touch devices */
    @media (hover: none) and (pointer: coarse) {
      .card {
        transition: none;
      }
      
      .feature-card {
        transition: none;
      }
    }
  </style>
</head>
<body>

<?php
session_start();
?>
<header>
  <div class="header-content">
    <div class="logo">
      <span class="logo-icon">🌾</span>
      <h1>AgriMarketplace</h1>
    </div>
    <nav>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#products">Products</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="dashboard.php" class="btn">My Dashboard</a></li>
          <li><a href="logout.php" class="btn">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php" class="btn">Seller Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>

<section class="hero" id="home">
  <div class="hero-slides active" style="background-image: url('img/img01.jpg');"></div>
  <div class="hero-slides" style="background-image: url('img/img02.jpg');"></div>
  <div class="hero-slides" style="background-image: url('img/img03.jpg');"></div>
  <div class="hero-content">
    <h2>Fresh Farm Produce, Direct from Farmers</h2>
    <p>Connect with local farmers and buy the freshest vegetables, fruits, grains, and dairy products directly from the source. Quality you can trust, prices you'll love.</p>
    <a href="#products" class="btn">Shop Now</a>
  </div>
</section>

<section class="features">
  <h2>Why Choose Our Marketplace?</h2>
  <div class="features-container">
    <div class="feature-card">
      <h3>🌱 Fresh & Organic</h3>
      <p>All products are grown using organic methods, ensuring the highest quality and nutritional value.</p>
    </div>
    <div class="feature-card">
      <h3>💰 Direct from Farmers</h3>
      <p>Buy directly from farmers without middlemen, ensuring fair prices for both parties.</p>
    </div>
    <div class="feature-card">
      <h3>🚚 Local Delivery</h3>
      <p>Fast and reliable delivery from local farmers to your doorstep within 24 hours.</p>
    </div>
  </div>
</section>

<section class="products-section" id="products">
  <div class="section-header">
    <h2>Featured Products</h2>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php" class="btn">Manage Products</a>
    <?php else: ?>
      <a href="login.php" class="btn">Sell Your Products</a>
    <?php endif; ?>
  </div>

  <div id="filter">
    <label>Filter by Category:</label>
    <select onchange="filterProducts(this.value)">
      <option value="all">All Products</option>
      <option value="fruit">Fruits</option>
      <option value="vegetable">Vegetables</option>
      <option value="grain">Grains</option>
      <option value="dairy">Dairy</option>
      <option value="livestock">Livestock</option>
    </select>
  </div>

  <div id="products-container" class="grid">
    <!-- Products will be loaded here via AJAX -->
  </div>
</section>

<footer>
  <div class="footer-content">
    <div class="footer-column">
      <h3>AgriMarketplace</h3>
      <p>Connecting farmers with consumers for fresh, local, and sustainable produce.</p>
    </div>
    <div class="footer-column">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#products">Products</a></li>
        <li><a href="#">How It Works</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="dashboard.php">My Dashboard</a></li>
        <?php else: ?>
          <li><a href="register.php">Farmer Registration</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="footer-column">
      <h3>Contact Us</h3>
      <ul>
        <li>Email: info@agrimarketplace.ng</li>
        <li>Phone: +234 800 000 0000</li>
        <li>Address: Kano, Nigeria</li>
      </ul>
    </div>
  </div>
  <div class="copyright">
    <p>&copy; 2023 AgriMarketplace. All rights reserved.</p>
  </div>
</footer>

<script>
  let currentFilter = "all";

  // Hero slide show functionality
  let slideIndex = 0;
  const slides = document.querySelectorAll('.hero-slides');
  
  function showSlides() {
    // Hide all slides
    slides.forEach(slide => slide.classList.remove('active'));
    
    // Move to next slide
    slideIndex++;
    if (slideIndex >= slides.length) {
      slideIndex = 0;
    }
    
    // Show current slide
    slides[slideIndex].classList.add('active');
    
    // Change slide every 5 seconds
    setTimeout(showSlides, 5000);
  }
  
  // Start the slide show
  showSlides();

  function filterProducts(filter) {
    currentFilter = filter;
    loadProducts(filter);
  }

  function loadProducts(filter = "all") {
    // Show loading indicator
    const container = document.getElementById("products-container");
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
    const container = document.getElementById("products-container");
    container.innerHTML = "";

    if (products.length === 0) {
      container.innerHTML = "<p style='padding: 20px; text-align: center; font-size: 1.2rem; grid-column: 1 / -1;'>No products found in this category.</p>";
      return;
    }

    products.forEach(p => {
      const card = document.createElement("div");
      card.className = "card";

      let mediaHTML = '';
      if (p.media_url) {
        if (p.media_type && p.media_type.startsWith("image")) {
          mediaHTML = `<img src="${p.media_url}" alt="${p.name}">`;
        } else if (p.media_type && p.media_type.startsWith("video")) {
          mediaHTML = `<video controls>
                         <source src="${p.media_url}" type="${p.media_type}">
                         Your browser does not support the video tag.
                       </video>`;
        }
      } else {
        mediaHTML = `<img src="https://placehold.co/400x300?text=No+Image" alt="${p.name}">`;
      }

      card.innerHTML = `
        ${mediaHTML}
        <span class="category">${p.category.charAt(0).toUpperCase() + p.category.slice(1)}</span>
        <h3>${p.name}</h3>
        <p>${p.description}</p>
        <p class="price">₦${p.price}</p>
        <p><strong>Phone:</strong> ${p.phone}</p>
        <p><strong>Location:</strong> ${p.location}</p>
      `;

      container.appendChild(card);
    });
  }

  // Initialize the page
  loadProducts();

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
</script>

</body>
</html>