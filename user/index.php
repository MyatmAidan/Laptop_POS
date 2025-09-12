<?php
// Start session
session_start();
require_once '../auth.php';
require_once '../database/dbrequire.php';

// Require authentication (but not admin)
requireAuth();

// Redirect if admin tries to access user area
if (isAdmin()) {
  header('Location: ../admin/index.php');
  exit;
}

include 'includes/header.php';
include 'includes/nav.php';
?>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center min-vh-75">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1 class="hero-title">
            Discover Premium <span class="text-gradient">Laptops</span> & Tech
          </h1>
          <p class="hero-subtitle">
            Experience cutting-edge technology with our curated collection of high-performance laptops,
            accessories, and innovative gadgets. Quality meets affordability.
          </p>
          <div class="hero-buttons">
            <a href="store.php" class="btn btn-primary btn-lg me-3">
              <i class="fas fa-shopping-bag me-2"></i>Shop Now
            </a>
            <a href="contact.php" class="btn btn-outline-primary btn-lg">
              <i class="fas fa-info-circle me-2"></i>Learn More
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image">
          <img src="img/gallery_perform.png" alt="Premium Laptops" class="img-fluid rounded-4 shadow-lg">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mb-3">
            <i class="fas fa-shipping-fast fa-3x text-primary"></i>
          </div>
          <h4>Fast Shipping</h4>
          <p class="text-muted">Free shipping on orders over $50. Get your tech delivered quickly and securely.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mb-3">
            <i class="fas fa-shield-alt fa-3x text-success"></i>
          </div>
          <h4>Secure Shopping</h4>
          <p class="text-muted">Your data is protected with industry-leading security and encryption.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mb-3">
            <i class="fas fa-headset fa-3x text-warning"></i>
          </div>
          <h4>24/7 Support</h4>
          <p class="text-muted">Our expert team is always here to help with any questions or concerns.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Shop by Category</h2>
      <p class="section-subtitle">Find exactly what you're looking for in our organized categories</p>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="category-card">
          <a href="store.php?category=1" class="category-link">
            <div class="category-image">
              <img src="img/home-shopmac.png" alt="Mac Laptops" class="img-fluid">
            </div>
            <div class="category-overlay">
              <h5>Mac Laptops</h5>
              <span class="category-count">Premium Apple Devices</span>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="category-card">
          <a href="store.php?category=11" class="category-link">
            <div class="category-image">
              <img src="img/home-shopiphone.png" alt="Windows Laptops" class="img-fluid">
            </div>
            <div class="category-overlay">
              <h5>Windows Laptops</h5>
              <span class="category-count">Powerful Performance</span>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="category-card">
          <a href="store.php?category=7" class="category-link">
            <div class="category-image">
              <img src="img/home-shopipad.png" alt="Gaming Laptops" class="img-fluid">
            </div>
            <div class="category-overlay">
              <h5>Gaming Laptops</h5>
              <span class="category-count">Ultimate Gaming Experience</span>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="category-card">
          <a href="store.php?category=1" class="category-link">
            <div class="category-image">
              <img src="img/home-shopipod.png" alt="Accessories" class="img-fluid">
            </div>
            <div class="category-overlay">
              <h5>Accessories</h5>
              <span class="category-count">Complete Your Setup</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <div class="newsletter-content">
          <h3>Stay Updated</h3>
          <p class="text-muted">Subscribe to our newsletter for the latest tech news, exclusive offers, and product updates.</p>
          <form class="newsletter-form">
            <div class="input-group mb-3">
              <input type="email" class="form-control" placeholder="Enter your email address" required>
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-paper-plane me-2"></i>Subscribe
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
    margin-top: -1rem;
  }

  .min-vh-75 {
    min-height: 75vh;
  }

  .hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
  }

  .text-gradient {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    line-height: 1.6;
  }

  .hero-buttons .btn {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
  }

  .hero-buttons .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  }

  .hero-image img {
    border-radius: 1rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }

  .feature-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
  }

  .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }

  .feature-icon {
    color: var(--primary-color);
  }

  .section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--dark-color);
  }

  .section-subtitle {
    font-size: 1.125rem;
    color: var(--gray-color);
    max-width: 600px;
    margin: 0 auto;
  }

  .category-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }

  .category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  }

  .category-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .category-image {
    position: relative;
    overflow: hidden;
  }

  .category-image img {
    transition: transform 0.3s ease;
  }

  .category-card:hover .category-image img {
    transform: scale(1.1);
  }

  .category-overlay {
    padding: 1.5rem;
    text-align: center;
  }

  .category-overlay h5 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
  }

  .category-count {
    color: var(--gray-color);
    font-size: 0.875rem;
  }

  .newsletter-section {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  }

  .newsletter-content h3 {
    color: var(--dark-color);
    margin-bottom: 1rem;
    font-weight: 700;
  }

  .newsletter-form .form-control {
    border-radius: 0.75rem 0 0 0.75rem;
    border: 2px solid var(--border-color);
    padding: 0.75rem 1rem;
  }

  .newsletter-form .btn {
    border-radius: 0 0.75rem 0.75rem 0;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
  }

  @media (max-width: 768px) {
    .hero-title {
      font-size: 2.5rem;
    }

    .hero-subtitle {
      font-size: 1.125rem;
    }

    .hero-buttons .btn {
      display: block;
      width: 100%;
      margin-bottom: 1rem;
    }

    .hero-image {
      margin-top: 2rem;
    }
  }
</style>

<?php
include 'includes/footer.php';
?>